<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentConfiguration;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Handle the payment status callback
     */
    public function status(Request $request)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[PAYMENT STATUS] PROSES DIMULAI');
        Log::channel('payment')->info('[PAYMENT STATUS] Fungsi dipanggil: PaymentController::status()');
        Log::channel('payment')->info('[PAYMENT STATUS] Timestamp: ' . now());
        Log::channel('payment')->info('[PAYMENT STATUS] Data Input (query params):', $request->all());

        // Get school code from request
        $schoolId = $request->query('school_id');
        if (!$schoolId) {
            Log::channel('payment')->warning('[PAYMENT STATUS] ❌ school_id TIDAK ADA di request');
            Log::channel('payment')->info('================================================================================');
            return response()->json(['error' => 'School Id is required'], 400);
        }

        // Get school details from main database
        Log::channel('payment')->info('[PAYMENT STATUS][STEP 1] Memanggil School::on("mysql")->where("id", ' . $schoolId . ') — mencari sekolah...');
        $school = School::on('mysql')->where('id', $schoolId)->first();

        if (!$school) {
            Log::channel('payment')->warning('[PAYMENT STATUS][STEP 1] School TIDAK DITEMUKAN');
            Log::channel('payment')->info('================================================================================');
            return response()->json(['error' => 'School not found'], 404);
        }

        Log::channel('payment')->info('[PAYMENT STATUS][STEP 1] School DITEMUKAN:', [
            'school_id' => $school->id,
            'school_name' => $school->name,
            'database_name' => $school->database_name,
        ]);

        // Set up school database connection
        Log::channel('payment')->info('[PAYMENT STATUS][STEP 2] Beralih ke database sekolah: ' . $school->database_name);
        Config::set('database.connections.school.database', $school->database_name);
        DB::purge('school');
        DB::connection('school')->reconnect();
        DB::setDefaultConnection('school');

        // Get payment gateway configuration from school database
        Log::channel('payment')->info('[PAYMENT STATUS][STEP 3] Memanggil PaymentConfiguration::where("school_id", ' . $school->id . ') — mencari payment gateway...');
        $paymentGateway = PaymentConfiguration::where('school_id', $school->id)->where('status', 1)->first();

        if (!$paymentGateway) {
            Log::channel('payment')->warning('[PAYMENT STATUS][STEP 3] Payment Gateway TIDAK DITEMUKAN');
            Log::channel('payment')->info('================================================================================');
            return response()->json(['error' => 'Payment Gateway not found'], 404);
        }

        Log::channel('payment')->info('[PAYMENT STATUS][STEP 3] Payment Gateway DITEMUKAN:', [
            'payment_method' => $paymentGateway->payment_method,
            'status' => $paymentGateway->status,
        ]);

        if ($paymentGateway->payment_method == 'Paystack') {
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 4] Payment method: Paystack');

            // Get payment reference from request
            $reference = $request->query('reference');
            if (!$reference) {
                Log::channel('payment')->warning('[PAYMENT STATUS] ❌ Transaction reference kosong');
                Log::channel('payment')->info('================================================================================');
                return response()->json(['error' => 'Transaction reference is required'], 400);
            }

            // Get payment status from request
            $status = $request->query('status');
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 4] Paystack status dari query:', [
                'reference' => $reference,
                'status' => $status ?? 'NULL',
            ]);

            // Handle cancelled payment
            if ($status === 'cancelled') {
                Log::channel('payment')->warning('[PAYMENT STATUS] Payment DIBATALKAN:', [
                    'reference' => $reference,
                    'school_id' => $schoolId,
                ]);

                // Update payment transaction status to failed
                $paymentTransaction = PaymentTransaction::where('order_id', $reference)->first();
                if ($paymentTransaction) {
                    $paymentTransaction->update(['payment_status' => 'failed']);
                    Log::channel('payment')->info('[PAYMENT STATUS] PaymentTransaction diupdate ke "failed"');
                }

                Log::channel('payment')->info('================================================================================');
                return redirect()->route('payment.status', ['status' => 'cancelled', 'school_id' => $schoolId, 'trxref' => $reference, 'reference' => $reference])->with('error', 'Payment was cancelled.');
            }

            // For successful payments, verify with Paystack API
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 5] Memanggil Http::get() — verifikasi pembayaran ke Paystack API...');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paymentGateway->secret_key,
                'Content-Type' => 'application/json',
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            $data = $response->json();
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 5] Paystack verification response:', [
                'http_status' => $response->status(),
                'paystack_status' => $data['data']['status'] ?? 'N/A',
            ]);

            if ($response->successful() && isset($data['data']['status']) && $data['data']['status'] === 'success') {
                Log::channel('payment')->info('[PAYMENT STATUS] ✅ Paystack payment TERVERIFIKASI');
                Log::channel('payment')->info('================================================================================');

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'transaction' => $data['data']
                ]);
            }
            else {
                Log::channel('payment')->error('[PAYMENT STATUS] ❌ Paystack payment verification GAGAL:', [
                    'reference' => $reference,
                    'response_message' => $data['message'] ?? 'Unknown',
                ]);

                // Update payment transaction status to failed
                $paymentTransaction = PaymentTransaction::where('order_id', $reference)->first();
                if ($paymentTransaction) {
                    $paymentTransaction->update(['payment_status' => 'failed']);
                    Log::channel('payment')->info('[PAYMENT STATUS] PaymentTransaction diupdate ke "failed"');
                }

                Log::channel('payment')->info('================================================================================');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                    'error' => $data['message'] ?? 'Unknown error'
                ]);
            }
        }
        else if ($paymentGateway->payment_method == 'Flutterwave') {
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 4] Payment method: Flutterwave');

            // Flutterwave implementation
            $paymentTransactionId = $request->query('tx_ref');
            $transactionId = $request->query('transaction_id'); // only present if success
            $status = $request->query('status');

            Log::channel('payment')->info('[PAYMENT STATUS][STEP 4] Flutterwave callback data:', [
                'tx_ref' => $paymentTransactionId ?? 'NULL',
                'transaction_id' => $transactionId ?? 'NULL',
                'status' => $status ?? 'NULL',
            ]);

            if ($status === 'cancelled') {
                Log::channel('payment')->warning('[PAYMENT STATUS] Flutterwave payment DIBATALKAN');
                // Mark transaction as failed/cancelled
                $paymentTransaction = PaymentTransaction::where('order_id', $paymentTransactionId)->first();
                if ($paymentTransaction) {
                    $paymentTransaction->update(['payment_status' => 'failed']);
                    Log::channel('payment')->info('[PAYMENT STATUS] PaymentTransaction diupdate ke "failed"');
                }

                Log::channel('payment')->info('================================================================================');
                return redirect()->route('payment.cancel')
                    ->with('error', 'Payment was cancelled.');
            }
            if (!$paymentTransactionId) {
                Log::channel('payment')->warning('[PAYMENT STATUS] ❌ Transaction ID kosong');
                Log::channel('payment')->info('================================================================================');
                return response()->json(['error' => 'Transaction ID is required'], 400);
            }

            $paymentTransaction = PaymentTransaction::where('order_id', $paymentTransactionId)->first();

            if (!$paymentTransaction) {
                Log::channel('payment')->warning('[PAYMENT STATUS] ❌ Transaction TIDAK DITEMUKAN');
                Log::channel('payment')->info('================================================================================');
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            if ($paymentTransaction->payment_status === "succeed") {
                Log::channel('payment')->info('[PAYMENT STATUS] Transaction sudah diproses sebelumnya — skip');
                Log::channel('payment')->info('================================================================================');
                return response()->json(['status' => 'success', 'message' => 'Transaction already processed']);
            }

            Log::channel('payment')->info('[PAYMENT STATUS][STEP 5] Memanggil Http::get() — verifikasi pembayaran ke Flutterwave API...');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paymentGateway->secret_key,
                'Content-Type' => 'application/json',
            ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            $data = $response->json();
            Log::channel('payment')->info('[PAYMENT STATUS][STEP 5] Flutterwave verification response:', [
                'http_status' => $response->status(),
                'flutterwave_status' => $data['status'] ?? 'N/A',
            ]);

            if ($response->successful() && $data['status'] === 'success') {
                Log::channel('payment')->info('[PAYMENT STATUS] ✅ Flutterwave payment TERVERIFIKASI');
                Log::channel('payment')->info('================================================================================');
                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'transaction' => $data['data']
                ]);
            }
            else {
                Log::channel('payment')->error('[PAYMENT STATUS] ❌ Flutterwave payment verification GAGAL');
                Log::channel('payment')->info('================================================================================');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                    'error' => $data['message'] ?? 'Unknown error'
                ]);
            }
        }
        else {
            Log::channel('payment')->warning('[PAYMENT STATUS] ❌ Payment method tidak dikenali: ' . $paymentGateway->payment_method);
            Log::channel('payment')->info('================================================================================');
            return response()->json(['error' => 'Payment Gateway not found'], 404);
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        Log::channel('payment')->info('[PAYMENT CANCEL] User melakukan pembatalan payment');
        return view('payment.cancel')->with('error', 'Payment was cancelled or failed.');
    }

    public function success()
    {
        Log::channel('payment')->info('[PAYMENT SUCCESS] Payment berhasil — menampilkan view payment.success');
        return view('payment.success')->with('success', 'Payment completed successfully.');
    }

    public function process(Request $request)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[DOKU PROCESS] PROSES DIMULAI');
        Log::channel('payment')->info('[DOKU PROCESS] Fungsi dipanggil: PaymentController::process()');
        Log::channel('payment')->info('[DOKU PROCESS] Timestamp: ' . now());
        Log::channel('payment')->info('[DOKU PROCESS] Data Input:', [
            'inquiry_id' => $request->inquiry_id,
            'amount' => $request->amount,
        ]);

        // ─── STEP 1: Validasi ───
        Log::channel('payment')->info('[DOKU PROCESS][STEP 1] Menjalankan validasi input...');
        $request->validate([
            'inquiry_id' => 'required',
            'amount' => 'required|numeric'
        ]);
        Log::channel('payment')->info('[DOKU PROCESS][STEP 1] Validasi BERHASIL');

        // ─── STEP 2: DOKU Config ───
        Log::channel('payment')->info('[DOKU PROCESS][STEP 2] Menyiapkan konfigurasi DOKU...');
        $clientId = env('DOKU_CLIENT_ID');
        $secretKey = env('DOKU_SECRET_KEY');

        $requestId = (string)Str::uuid();
        $requestTimestamp = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $requestTarget = '/checkout/v1/payment';
        $orderId = 'JT-' . time();
        $amount = $request->amount;

        Log::channel('payment')->info('[DOKU PROCESS][STEP 2] DOKU Config:', [
            'client_id' => $clientId ? substr($clientId, 0, 10) . '...' : 'NOT SET',
            'secret_key_exists' => $secretKey ? 'YA' : 'TIDAK',
            'request_id' => $requestId,
            'request_timestamp' => $requestTimestamp,
            'order_id' => $orderId,
            'amount' => $amount,
        ]);

        // ─── STEP 3: Construct Body ───
        Log::channel('payment')->info('[DOKU PROCESS][STEP 3] Membuat request body...');
        $body = [
            "order" => [
                "amount" => (int)$amount,
                "invoice_number" => $orderId,
                "currency" => "IDR",
                "callback_url" => url('/payment/success'),
                "callback_url_cancel" => url('/payment/cancel'),
                "notification_url" => url('/api/webhook/doku'),
            ],
            "payment" => [
                "type" => "SALE",
                "payment_due_date" => 60
            ]
        ];

        Log::channel('payment')->info('[DOKU PROCESS][STEP 3] Request Body:', $body);

        // ─── STEP 4: Generate Signature ───
        Log::channel('payment')->info('[DOKU PROCESS][STEP 4] Memanggil hash("sha256") dan hash_hmac("sha256") — generate signature...');
        $jsonBody = json_encode($body);
        $digest = base64_encode(hash('sha256', $jsonBody, true));

        $signatureRaw =
            "Client-Id:$clientId\n" .
            "Request-Id:$requestId\n" .
            "Request-Timestamp:$requestTimestamp\n" .
            "Request-Target:$requestTarget\n" .
            "Digest:$digest";

        $signature = base64_encode(
            hash_hmac('sha256', $signatureRaw, $secretKey, true)
        );

        $signatureHeader = "HMACSHA256=" . $signature;
        Log::channel('payment')->info('[DOKU PROCESS][STEP 4] Signature berhasil di-generate');

        // ─── STEP 5: Send to DOKU ───
        Log::channel('payment')->info('[DOKU PROCESS][STEP 5] Memanggil Http::post() — mengirim request ke DOKU sandbox API...');
        $response = Http::withHeaders([
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => $signatureHeader,
            'Digest' => $digest,
        ])->post('https://sandbox.doku.com/checkout/v1/payment', $body);

        $responseData = $response->json();

        Log::channel('payment')->info('[DOKU PROCESS][STEP 5] DOKU Response:', [
            'http_status' => $response->status(),
            'successful' => $response->successful() ? 'YA' : 'TIDAK',
        ]);

        $paymentUrl =
            $responseData['response']['payment']['url']
            ?? $responseData['payment']['url']
            ?? null;


        if ($response->successful() && $paymentUrl) {
            Log::channel('payment')->info('[DOKU PROCESS] ✅ PROSES SELESAI — payment URL berhasil didapat:', [
                'payment_url' => $paymentUrl,
                'order_id' => $orderId,
            ]);
            Log::channel('payment')->info('================================================================================');

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl
            ]);
        }

        Log::channel('payment')->error('[DOKU PROCESS] ❌ GAGAL — tidak bisa mendapatkan payment URL', [
            'response_body' => $responseData,
        ]);
        Log::channel('payment')->info('================================================================================');

        return response()->json([
            'success' => false,
            'message' => $responseData
        ], 500);
    }
}
