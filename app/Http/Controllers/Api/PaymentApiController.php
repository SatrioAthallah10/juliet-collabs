<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolInquiry;
use App\Jobs\SetupSchoolDatabase;
use Illuminate\Support\Str;
use App\Models\School;
use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PaymentApiController extends Controller
{
    /**
     * Menampilkan halaman verifikasi pembayaran (verify.blade.php)
     */
    public function verify($id)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[VERIFY] PROSES DIMULAI');
        Log::channel('payment')->info('[VERIFY] Fungsi dipanggil: PaymentApiController::verify()');
        Log::channel('payment')->info('[VERIFY] Parameter: inquiry_id = ' . $id);

        $inquiry = SchoolInquiry::find($id);

        if (!$inquiry) {
            Log::channel('payment')->warning('[VERIFY] Inquiry TIDAK DITEMUKAN dengan ID: ' . $id);
            Log::channel('payment')->info('================================================================================');
            abort(404);
        }

        Log::channel('payment')->info('[VERIFY] Inquiry DITEMUKAN:', [
            'inquiry_id' => $inquiry->id,
            'school_name' => $inquiry->school_name,
            'school_email' => $inquiry->school_email,
            'invoice_number' => $inquiry->invoice_number,
            'payment_status' => $inquiry->payment_status,
            'price' => $inquiry->price,
        ]);

        // Ambil package berdasarkan package_id
        $package = Package::find($inquiry->package_id);
        Log::channel('payment')->info('[VERIFY] Package:', [
            'package_id' => $package->id ?? 'NULL',
            'package_name' => $package->name ?? 'NULL',
            'charges' => $package->charges ?? 'NULL',
        ]);

        // Set expiry 24 jam dari created_at
        $expires_at = Carbon::parse($inquiry->created_at)->addHours(24);
        Log::channel('payment')->info('[VERIFY] Expiry: ' . $expires_at);

        Log::channel('payment')->info('[VERIFY] ✅ PROSES SELESAI — menampilkan view verify.blade.php');
        Log::channel('payment')->info('================================================================================');

        return view('verify', compact('inquiry', 'package', 'expires_at'));
    }

    /**
     * Memproses pembayaran dan generate URL pembayaran DOKU
     */
    public function process(Request $request)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[PAYMENT PROCESS] PROSES DIMULAI');
        Log::channel('payment')->info('[PAYMENT PROCESS] Fungsi dipanggil: PaymentApiController::process()');
        Log::channel('payment')->info('[PAYMENT PROCESS] Timestamp: ' . now());
        Log::channel('payment')->info('[PAYMENT PROCESS] Data Input:', [
            'inquiry_id' => $request->inquiry_id,
            'amount' => $request->amount,
        ]);

        // ─── STEP 1: Validasi ───
        Log::channel('payment')->info('[PAYMENT PROCESS][STEP 1] Menjalankan validasi input...');
        $request->validate([
            'inquiry_id' => 'required|exists:school_inquiries,id',
            'amount' => 'required|numeric',
        ]);
        Log::channel('payment')->info('[PAYMENT PROCESS][STEP 1] Validasi BERHASIL');

        // ─── STEP 2: Ambil Inquiry ───
        Log::channel('payment')->info('[PAYMENT PROCESS][STEP 2] Memanggil SchoolInquiry::find(' . $request->inquiry_id . ')...');
        $inquiry = SchoolInquiry::find($request->inquiry_id);
        Log::channel('payment')->info('[PAYMENT PROCESS][STEP 2] Inquiry ditemukan:', [
            'inquiry_id' => $inquiry->id,
            'school_name' => $inquiry->school_name,
            'school_email' => $inquiry->school_email,
            'current_payment_status' => $inquiry->payment_status,
            'price' => $inquiry->price,
        ]);

        try {
            // ─── STEP 3: Generate Invoice Number ───
            $invoiceNumber = $inquiry->invoice_number ?? 'INV-' . time() . '-' . $inquiry->id;
            $isNewInvoice = !$inquiry->invoice_number;

            if ($isNewInvoice) {
                Log::channel('payment')->info('[PAYMENT PROCESS][STEP 3] Invoice number BARU di-generate: ' . $invoiceNumber);
                $inquiry->update(['invoice_number' => $invoiceNumber]);
                Log::channel('payment')->info('[PAYMENT PROCESS][STEP 3] Invoice number disimpan ke database');
            }
            else {
                Log::channel('payment')->info('[PAYMENT PROCESS][STEP 3] Menggunakan invoice number YANG SUDAH ADA: ' . $invoiceNumber);
            }

            // ─── STEP 4: DOKU Configuration ───
            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 4] Menyiapkan konfigurasi DOKU...');
            $clientId = env('DOKU_CLIENT_ID');
            $secretKey = env('DOKU_SECRET_KEY');
            $dokuUrl = env('DOKU_URL', 'https://sandbox.doku.com');

            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 4] DOKU Config:', [
                'client_id' => $clientId ? substr($clientId, 0, 10) . '...' : 'NOT SET',
                'secret_key_exists' => $secretKey ? 'YA' : 'TIDAK',
                'doku_url' => $dokuUrl,
            ]);

            // ─── STEP 5: Construct DOKU Request ───
            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 5] Membuat request body DOKU Checkout V1...');
            $requestId = Str::uuid()->toString();
            $requestTimestamp = Carbon::now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
            $requestTarget = '/checkout/v1/payment';

            $requestBody = [
                'order' => [
                    'amount' => (int)$inquiry->price,
                    'invoice_number' => $invoiceNumber,
                    'currency' => 'IDR',
                    'callback_url' => route('payment.verify', ['id' => $inquiry->id]),
                    'callback_url_cancel' => url('/payment/cancel'),
                    'line_items' => [
                        [
                            'name' => 'Subscription Package',
                            'price' => (int)$inquiry->price,
                            'quantity' => 1
                        ]
                    ]
                ],
                'payment' => [
                    'payment_due_date' => 60,
                    'type' => 'SALE'
                ],
                'customer' => [
                    'id' => $inquiry->id,
                    'name' => $inquiry->school_name,
                    'email' => $inquiry->school_email,
                    'phone' => $inquiry->school_phone,
                ],
                'notification' => [
                    'url' => url('/api/webhook/doku')
                ]
            ];

            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 5] Request Body:', [
                'request_id' => $requestId,
                'request_timestamp' => $requestTimestamp,
                'request_target' => $requestTarget,
                'order_amount' => (int)$inquiry->price,
                'invoice_number' => $invoiceNumber,
                'callback_url' => route('payment.verify', ['id' => $inquiry->id]),
                'customer_name' => $inquiry->school_name,
                'customer_email' => $inquiry->school_email,
            ]);

            // ─── STEP 6: Generate Signature ───
            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 6] Memanggil hash("sha256") dan hash_hmac("sha256") — generate signature DOKU...');
            $digest = base64_encode(hash('sha256', json_encode($requestBody), true));
            $componentSignature = "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $requestTimestamp . "\n" .
                "Request-Target:" . $requestTarget . "\n" .
                "Digest:" . $digest;

            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 6] Signature berhasil di-generate');

            // ─── STEP 7: Send Request to DOKU ───
            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 7] Memanggil Http::post() — mengirim request ke DOKU API: ' . $dokuUrl . $requestTarget);
            $response = Http::withHeaders([
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => 'HMACSHA256=' . $signature,
                'Content-Type' => 'application/json',
            ])->post($dokuUrl . $requestTarget, $requestBody);

            Log::channel('payment')->info('[PAYMENT PROCESS][STEP 7] DOKU Response:', [
                'http_status' => $response->status(),
                'successful' => $response->successful() ? 'YA' : 'TIDAK',
            ]);

            if ($response->successful()) {
                $responseBody = $response->json();
                $paymentUrl = $responseBody['response']['payment']['url'] ?? null;

                if ($paymentUrl) {
                    Log::channel('payment')->info('[PAYMENT PROCESS] ✅ PROSES SELESAI — payment URL berhasil didapat');
                    Log::channel('payment')->info('[PAYMENT PROCESS] Hasil yang tercipta:', [
                        'payment_url' => $paymentUrl,
                        'invoice_number' => $invoiceNumber,
                    ]);
                    Log::channel('payment')->info('================================================================================');

                    return response()->json([
                        'success' => true,
                        'payment_url' => $paymentUrl
                    ]);
                }
                else {
                    Log::channel('payment')->error('[PAYMENT PROCESS] ❌ GAGAL — response DOKU tidak mengandung payment URL', ['response' => $responseBody]);
                    Log::channel('payment')->info('================================================================================');
                    return response()->json(['success' => false, 'message' => 'Gagal mendapatkan URL pembayaran dari DOKU'], 500);
                }
            }
            else {
                Log::channel('payment')->error('[PAYMENT PROCESS] ❌ GAGAL — DOKU API mengembalikan error', [
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);
                Log::channel('payment')->info('================================================================================');
                return response()->json(['success' => false, 'message' => 'Gagal menghubungi payment gateway'], 500);
            }

        }
        catch (\Exception $e) {
            Log::channel('payment')->error('[PAYMENT PROCESS] ❌ EXCEPTION TERJADI', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::channel('payment')->info('================================================================================');
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    /**
     * Webhook endpoint untuk menerima callback notification dari DOKU.
     * URL: POST /api/webhook/doku
     *
     * DOKU akan mengirim notifikasi ke URL ini setiap kali ada perubahan status pembayaran.
     * Harus selalu return HTTP 200 agar DOKU tidak retry.
     */
    public function dokuWebhook(Request $request)
    {
        Log::channel('webhook')->info('================================================================================');
        Log::channel('webhook')->info('[DOKU WEBHOOK] NOTIFIKASI DITERIMA');
        Log::channel('webhook')->info('[DOKU WEBHOOK] Fungsi dipanggil: PaymentApiController::dokuWebhook()');
        Log::channel('webhook')->info('[DOKU WEBHOOK] Timestamp: ' . now());
        Log::channel('webhook')->info('[DOKU WEBHOOK] Headers:', $request->headers->all());
        Log::channel('webhook')->info('[DOKU WEBHOOK] Body (raw payload):', $request->all());

        try {
            // ─── STEP 1: Parse payload ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 1] Parsing data dari payload DOKU...');
            $payload = $request->all();

            $invoiceNumber = $payload['order']['invoice_number']
                ?? $payload['invoice_number']
                ?? null;

            $transactionStatus = $payload['transaction']['status']
                ?? $payload['transaction_status']
                ?? $payload['status']
                ?? null;

            $amount = $payload['order']['amount']
                ?? $payload['amount']
                ?? null;

            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 1] Data yang terparsing:', [
                'invoice_number' => $invoiceNumber ?? 'TIDAK ADA',
                'transaction_status' => $transactionStatus ?? 'TIDAK ADA',
                'amount' => $amount ?? 'TIDAK ADA',
            ]);

            if (!$invoiceNumber) {
                Log::channel('webhook')->warning('[DOKU WEBHOOK][STEP 1] ⚠️ invoice_number KOSONG — proses dihentikan');
                Log::channel('webhook')->info('================================================================================');
                return response()->json(['message' => 'OK'], 200);
            }

            // ─── STEP 2: Verifikasi signature ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 2] Verifikasi signature DI-BYPASS (Sesuai Request User)');
            // $signature = $request->header('X-Signature')
            //     ?? $request->header('Signature')
            //     ?? null;

            // $secretKey = env('DOKU_SECRET_KEY');

            // if ($signature && $secretKey) {
            //     $rawBody = $request->getContent();
            //     $digest = base64_encode(hash('sha256', $rawBody, true));
            //     $componentSignature =
            //         "Client-Id:" . env('DOKU_CLIENT_ID') . "\n" .
            //         "Request-Id:" . ($request->header('Request-Id') ?? '') . "\n" .
            //         "Request-Timestamp:" . ($request->header('Request-Timestamp') ?? '') . "\n" .
            //         "Request-Target:/api/webhook/doku\n" .
            //         "Digest:" . $digest;

            //     $expectedSignature = base64_encode(
            //         hash_hmac('sha256', $componentSignature, $secretKey, true)
            //     );

            //     $cleanSignature = str_replace('HMACSHA256=', '', $signature);

            //     Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 2] Signature verification OK');
            // }
            // else {
            //     Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 2] Signature verification DILEWATI (header atau secret key tidak ada)');
            // }

            // ─── STEP 3: Cek status pembayaran ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 3] Mengecek apakah status pembayaran sukses...');
            $successStatuses = ['SUCCESS', 'PAID', 'SETTLED', 'success', 'paid', 'settled'];

            if (!in_array($transactionStatus, $successStatuses)) {
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 3] Status BUKAN sukses: ' . $transactionStatus . ' — proses dihentikan');
                Log::channel('webhook')->info('================================================================================');
                return response()->json(['message' => 'OK'], 200);
            }

            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 3] Status pembayaran SUKSES: ' . $transactionStatus);

            // ─── STEP 4: Cari inquiry ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 4] Memanggil SchoolInquiry::where("invoice_number", "' . $invoiceNumber . '")->lockForUpdate()...');
            DB::beginTransaction();
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 4] DB::beginTransaction() dipanggil');

            $inquiry = SchoolInquiry::where('invoice_number', $invoiceNumber)
                ->lockForUpdate()
                ->first();

            if (!$inquiry) {
                DB::rollBack();
                Log::channel('webhook')->warning('[DOKU WEBHOOK][STEP 4] Inquiry TIDAK DITEMUKAN untuk invoice: ' . $invoiceNumber);
                Log::channel('webhook')->info('================================================================================');
                return response()->json(['message' => 'OK'], 200);
            }

            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 4] Inquiry DITEMUKAN:', [
                'inquiry_id' => $inquiry->id,
                'school_name' => $inquiry->school_name,
                'school_email' => $inquiry->school_email,
                'current_payment_status' => $inquiry->payment_status,
            ]);

            // Cek apakah sudah pernah diproses
            if ($inquiry->payment_status === 'paid' || $inquiry->payment_status === 'success') {
                DB::commit();
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 4] Invoice SUDAH PERNAH DIBAYAR — skip proses');
                Log::channel('webhook')->info('================================================================================');
                return response()->json(['message' => 'Already processed'], 200);
            }

            // ─── STEP 5: Update payment status ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 5] Memanggil inquiry->update() — mengubah payment_status ke "success"...');
            $inquiry->update([
                'payment_status' => 'success',
                'payment_date' => now(),
            ]);
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 5] Payment status TERUPDATE:', [
                'inquiry_id' => $inquiry->id,
                'new_payment_status' => 'success',
                'payment_date' => now()->toDateTimeString(),
            ]);

            // ─── STEP 6: Buat school ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] Memanggil School::where("support_email", "' . $inquiry->school_email . '") — cek apakah school sudah ada...');
            $school = School::where('support_email', $inquiry->school_email)->first();

            if (!$school) {
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] School BELUM ADA — memanggil School::create() untuk membuat school baru...');
                $school = School::create([
                    'name' => $inquiry->school_name,
                    'support_email' => $inquiry->school_email,
                    'support_phone' => $inquiry->school_phone,
                    'address' => $inquiry->school_address ?? null,
                    'tagline' => $inquiry->school_tagline ?? null,
                    'status' => 1,
                    'installed' => 1,
                ]);
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] School BARU TERCIPTA:', [
                    'school_id' => $school->id,
                    'name' => $school->name,
                    'support_email' => $school->support_email,
                ]);
            }
            else {
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] School SUDAH ADA (ID: ' . $school->id . ') — skip pembuatan');
            }

            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] Memanggil school->update() — set code dan database_name...');
            $school->update([
                'status' => 1,
                'installed' => 1,
                'code' => 'SCH' . str_pad($school->id, 5, '0', STR_PAD_LEFT),
                'database_name' => 'eschool_saas_' . $school->id . '_' . Str::slug($school->name),
            ]);

            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 6] School TERUPDATE:', [
                'school_id' => $school->id,
                'code' => $school->code,
                'database_name' => $school->database_name,
            ]);

            // ─── STEP 7: Buat admin user ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Memanggil User::where("email", "' . $inquiry->school_email . '") — cek apakah admin sudah ada...');
            $existingAdmin = User::where('email', $inquiry->school_email)->first();

            if (!$existingAdmin) {
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Admin BELUM ADA — memanggil User::create() untuk membuat admin baru...');
                $admin = User::create([
                    'first_name' => 'School',
                    'last_name' => 'Admin',
                    'mobile' => $inquiry->school_phone,
                    'email' => $inquiry->school_email,
                    'password' => Hash::make($inquiry->school_phone),
                    'school_id' => $school->id,
                    'image' => 'dummy_logo.jpg',
                ]);

                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Admin user TERCIPTA:', [
                    'user_id' => $admin->id,
                    'email' => $admin->email,
                    'school_id' => $admin->school_id,
                ]);

                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Memanggil Role::updateOrCreate() — assign role "School Admin"...');
                $role = Role::withoutGlobalScope('school')->updateOrCreate(
                ['name' => 'School Admin', 'school_id' => $school->id],
                ['custom_role' => 0, 'editable' => 0, 'guard_name' => 'web']
                );
                $admin->assignRole($role);

                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Role TERCIPTA/TERUPDATE:', [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                ]);

                $school->update(['admin_id' => $admin->id]);
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] School admin_id diupdate ke: ' . $admin->id);
            }
            else {
                Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 7] Admin SUDAH ADA (ID: ' . $existingAdmin->id . ') — skip pembuatan');
            }

            DB::commit();
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 8] DB::commit() — semua data tersimpan ke database');

            // ─── STEP 8: Setup school database ───
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 9] Memanggil SetupSchoolDatabase::dispatch(...)->afterResponse() — membuat database sekolah di background (setelah response)...');
            Log::channel('webhook')->info('[DOKU WEBHOOK][STEP 9] Parameter dispatch:', [
                'school_id' => $school->id,
                'package_id' => $inquiry->package_id,
                'school_code' => $school->code,
            ]);

            SetupSchoolDatabase::dispatch(
                $school->id,
                $inquiry->package_id,
                $school->code,
            );

            Log::channel('webhook')->info('[DOKU WEBHOOK] ✅ PROSES SELESAI — Sekolah berhasil diaktifkan');
            Log::channel('webhook')->info('[DOKU WEBHOOK] Hasil yang tercipta:', [
                'school_id' => $school->id,
                'school_name' => $school->name,
                'school_code' => $school->code,
                'database_name' => $school->database_name,
                'admin_email' => $inquiry->school_email,
                'payment_status' => 'success',
                'invoice_number' => $invoiceNumber,
            ]);
            Log::channel('webhook')->info('================================================================================');

            return response()->json(['message' => 'OK'], 200);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::channel('webhook')->error('[DOKU WEBHOOK] ❌ EXCEPTION TERJADI — DB::rollBack() dipanggil', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            Log::channel('webhook')->info('================================================================================');
            return response()->json(['message' => 'OK'], 200);
        }
    }

    public function activateAfterPayment(Request $request)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[ACTIVATE] PROSES DIMULAI');
        Log::channel('payment')->info('[ACTIVATE] Fungsi dipanggil: PaymentApiController::activateAfterPayment()');
        Log::channel('payment')->info('[ACTIVATE] Timestamp: ' . now());
        Log::channel('payment')->info('[ACTIVATE] Data Input:', [
            'invoice_number' => $request->invoice_number,
        ]);

        // ─── STEP 1: Validasi ───
        Log::channel('payment')->info('[ACTIVATE][STEP 1] Menjalankan validasi input...');
        $request->validate([
            'invoice_number' => 'required|string'
        ]);
        Log::channel('payment')->info('[ACTIVATE][STEP 1] Validasi BERHASIL');

        DB::beginTransaction();
        Log::channel('payment')->info('[ACTIVATE][STEP 2] DB::beginTransaction() dipanggil');

        try {
            // ─── STEP 2: Cari inquiry ───
            Log::channel('payment')->info('[ACTIVATE][STEP 3] Memanggil SchoolInquiry::where("invoice_number", "' . $request->invoice_number . '")->lockForUpdate()...');
            $inquiry = SchoolInquiry::where(
                'invoice_number',
                $request->invoice_number
            )->lockForUpdate()->first();

            if (!$inquiry) {
                DB::rollBack();
                Log::channel('payment')->warning('[ACTIVATE][STEP 3] Inquiry TIDAK DITEMUKAN untuk invoice: ' . $request->invoice_number);
                Log::channel('payment')->info('================================================================================');
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            Log::channel('payment')->info('[ACTIVATE][STEP 3] Inquiry DITEMUKAN:', [
                'inquiry_id' => $inquiry->id,
                'school_name' => $inquiry->school_name,
                'school_email' => $inquiry->school_email,
                'payment_status' => $inquiry->payment_status,
            ]);

            // ─── STEP 3: Update payment status ───
            Log::channel('payment')->info('[ACTIVATE][STEP 4] Memanggil inquiry->update() — mengubah payment_status ke "success"...');
            $inquiry->update([
                'payment_status' => 'success',
                'payment_date' => now()
            ]);
            Log::channel('payment')->info('[ACTIVATE][STEP 4] Payment status TERUPDATE');

            if ($inquiry->payment_status === 'paid' || $inquiry->payment_status === 'success') {
                DB::commit();
                Log::channel('payment')->info('[ACTIVATE] Invoice SUDAH DIAKTIFKAN sebelumnya — skip');
                Log::channel('payment')->info('================================================================================');
                return response()->json([
                    'success' => true,
                    'message' => 'Already activated'
                ]);
            }



            // ─── STEP 4: Create School ───
            Log::channel('payment')->info('[ACTIVATE][STEP 5] Memanggil School::where("support_email", "' . $inquiry->school_email . '") — cek apakah school sudah ada...');
            $school = School::where('support_email', $inquiry->school_email)->first();

            if (!$school) {
                Log::channel('payment')->info('[ACTIVATE][STEP 5] School BELUM ADA — memanggil School::create()...');
                $school = School::create([
                    'name' => $inquiry->school_name,
                    'support_email' => $inquiry->school_email,
                    'support_phone' => $inquiry->school_phone,
                    'address' => $inquiry->school_address ?? null,
                    'tagline' => $inquiry->school_tagline ?? null,
                    'status' => 1,
                    'installed' => 1,
                ]);
                Log::channel('payment')->info('[ACTIVATE][STEP 5] School BARU TERCIPTA:', [
                    'school_id' => $school->id,
                    'name' => $school->name,
                ]);
            }
            else {
                Log::channel('payment')->info('[ACTIVATE][STEP 5] School SUDAH ADA (ID: ' . $school->id . ')');
            }

            Log::channel('payment')->info('[ACTIVATE][STEP 6] Memanggil school->update() — set code dan database_name...');
            $school->update([
                'status' => 1,
                'installed' => 1,
                'code' => 'SCH' . str_pad($school->id, 5, '0', STR_PAD_LEFT),
                'database_name' => 'eschool_saas_' . $school->id . '_' . Str::slug($school->name)
            ]);

            Log::channel('payment')->info('[ACTIVATE][STEP 6] School TERUPDATE:', [
                'school_id' => $school->id,
                'code' => $school->code,
                'database_name' => $school->database_name,
            ]);

            // ─── STEP 5: Create Admin ───
            Log::channel('payment')->info('[ACTIVATE][STEP 7] Memanggil User::where("email", "' . $inquiry->school_email . '") — cek apakah admin sudah ada...');
            $existingAdmin = User::where('email', $inquiry->school_email)->first();

            if (!$existingAdmin) {
                Log::channel('payment')->info('[ACTIVATE][STEP 7] Admin BELUM ADA — memanggil User::create()...');
                $admin = User::create([
                    'first_name' => 'School',
                    'last_name' => 'Admin',
                    'mobile' => $inquiry->school_phone,
                    'email' => $inquiry->school_email,
                    'password' => Hash::make($inquiry->school_phone),
                    'school_id' => $school->id,
                    'image' => 'dummy_logo.jpg'
                ]);

                Log::channel('payment')->info('[ACTIVATE][STEP 7] Admin user TERCIPTA:', [
                    'user_id' => $admin->id,
                    'email' => $admin->email,
                    'school_id' => $admin->school_id,
                ]);

                Log::channel('payment')->info('[ACTIVATE][STEP 7] Memanggil Role::updateOrCreate() — assign role "School Admin"...');
                $role = Role::withoutGlobalScope('school')->updateOrCreate(
                ['name' => 'School Admin', 'school_id' => $school->id],
                ['custom_role' => 0, 'editable' => 0, 'guard_name' => 'web']
                );
                $admin->assignRole($role);

                Log::channel('payment')->info('[ACTIVATE][STEP 7] Role assigned:', [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                ]);

                $school->update([
                    'admin_id' => $admin->id
                ]);
                Log::channel('payment')->info('[ACTIVATE][STEP 7] School admin_id diupdate ke: ' . $admin->id);
            }
            else {
                Log::channel('payment')->info('[ACTIVATE][STEP 7] Admin SUDAH ADA (ID: ' . $existingAdmin->id . ') — skip');
            }

            DB::commit();
            Log::channel('payment')->info('[ACTIVATE][STEP 8] DB::commit() — semua data tersimpan');

            // ─── STEP 6: Setup database ───
            Log::channel('payment')->info('[ACTIVATE][STEP 9] Memanggil SetupSchoolDatabase::dispatch(...)->afterResponse() — membuat database sekolah di background (setelah response)...');
            Log::channel('payment')->info('[ACTIVATE][STEP 9] Parameter dispatch:', [
                'school_id' => $school->id,
                'package_id' => $inquiry->package_id,
                'school_code' => $school->code,
            ]);

            SetupSchoolDatabase::dispatch(
                $school->id,
                $inquiry->package_id,
                $school->code,
            );

            Log::channel('payment')->info('[ACTIVATE] ✅ PROSES SELESAI — Sekolah berhasil diaktifkan');
            Log::channel('payment')->info('[ACTIVATE] Hasil yang tercipta:', [
                'school_id' => $school->id,
                'school_name' => $school->name,
                'school_code' => $school->code,
                'database_name' => $school->database_name,
                'admin_email' => $inquiry->school_email,
            ]);
            Log::channel('payment')->info('================================================================================');

            return response()->json([
                'success' => true,
                'message' => 'School activated successfully'
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::channel('payment')->error('[ACTIVATE] ❌ EXCEPTION TERJADI — DB::rollBack() dipanggil', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            Log::channel('payment')->info('================================================================================');

            return response()->json([
                'success' => false,
                'message' => 'Activation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkActivation(Request $request)
    {
        Log::channel('payment')->info('================================================================================');
        Log::channel('payment')->info('[CHECK ACTIVATION] PROSES DIMULAI');
        Log::channel('payment')->info('[CHECK ACTIVATION] Fungsi dipanggil: PaymentApiController::checkActivation()');
        Log::channel('payment')->info('[CHECK ACTIVATION] Data Input:', [
            'invoice_number' => $request->invoice_number,
        ]);

        $request->validate([
            'invoice_number' => 'required|string'
        ]);

        Log::channel('payment')->info('[CHECK ACTIVATION] Memanggil SchoolInquiry::where("invoice_number", "' . $request->invoice_number . '")...');
        $inquiry = SchoolInquiry::where(
            'invoice_number',
            $request->invoice_number
        )->first();

        if (!$inquiry) {
            Log::channel('payment')->warning('[CHECK ACTIVATION] Inquiry TIDAK DITEMUKAN');
            Log::channel('payment')->info('================================================================================');
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        Log::channel('payment')->info('[CHECK ACTIVATION] Inquiry ditemukan:', [
            'inquiry_id' => $inquiry->id,
            'payment_status' => $inquiry->payment_status,
            'school_email' => $inquiry->school_email,
        ]);

        $admin = \App\Models\User::where(
            'email',
            $inquiry->school_email
        )->first();

        if (!$admin) {
            Log::channel('payment')->info('[CHECK ACTIVATION] Admin user BELUM TERCIPTA — school belum disetup');
            Log::channel('payment')->info('================================================================================');
            return response()->json([
                'success' => true,
                'message' => 'Inquiry found but school not created yet',
                'data' => [
                    'inquiry_status' => $inquiry->payment_status
                ]
            ]);
        }

        Log::channel('payment')->info('[CHECK ACTIVATION] Admin ditemukan:', [
            'admin_id' => $admin->id,
            'admin_email' => $admin->email,
            'school_id' => $admin->school_id,
        ]);

        $school = \App\Models\School::with('subscription')
            ->find($admin->school_id);

        if (!$school) {
            Log::channel('payment')->info('[CHECK ACTIVATION] School BELUM TERCIPTA');
            Log::channel('payment')->info('================================================================================');
            return response()->json([
                'success' => true,
                'message' => 'Inquiry found but school not created yet',
                'data' => [
                    'inquiry_status' => $inquiry->payment_status
                ]
            ]);
        }

        Log::channel('payment')->info('[CHECK ACTIVATION] School ditemukan:', [
            'school_id' => $school->id,
            'school_name' => $school->name,
            'school_status' => $school->status,
            'has_subscription' => $school->subscription ? 'YA' : 'TIDAK',
        ]);

        $admin = \App\Models\User::where(
            'school_id',
            $school->id
        )->role('Admin')->first();

        Log::channel('payment')->info('[CHECK ACTIVATION] ✅ PROSES SELESAI — data activation dikembalikan');
        Log::channel('payment')->info('================================================================================');

        return response()->json([
            'success' => true,
            'data' => [
                'inquiry_status' => $inquiry->payment_status,
                'school' => [
                    'id' => $school->id,
                    'name' => $school->name,
                    'email' => $school->email,
                    'status' => $school->status,
                    'admin_id' => $school->admin_id
                ],
                'admin' => $admin ? [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'roles' => $admin->getRoleNames()
                ] : null,
                'subscription' => $school->subscription ?? null
            ]
        ]);
    }




    /**
     * Lightweight endpoint for frontend polling.
     * Returns payment_status for a given inquiry ID.
     * Used by verify.blade.php to detect when webhook has updated payment.
     */
    public function checkPaymentStatus($id)
    {
        $inquiry = SchoolInquiry::find($id);

        if (!$inquiry) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $inquiry->payment_status,
            'invoice_number' => $inquiry->invoice_number,
        ]);
    }

    public function success(Request $request)
    {
        Log::channel('payment')->info('[PAYMENT SUCCESS] Fungsi dipanggil: PaymentApiController::success()');
        Log::channel('payment')->info('[PAYMENT SUCCESS] User di-redirect ke /dashboard');
        return redirect('/dashboard');
    }

}