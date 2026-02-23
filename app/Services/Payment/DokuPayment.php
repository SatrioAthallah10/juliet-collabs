namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class DokuPayment implements PaymentInterface
{
    public function pay($data)
    {
        $response = Http::withHeaders([
            'Client-Id' => config('services.doku.client_id'),
            'Request-Id' => uniqid(),
            'Request-Timestamp' => now()->toIso8601String(),
            'Signature' => $this->generateSignature($data)
        ])->post(config('services.doku.endpoint'), [
            "order" => [
                "invoice_number" => $data['invoice'],
                "amount" => $data['amount']
            ],
            "customer" => [
                "name" => $data['name'],
                "email" => $data['email']
            ]
        ]);

        return $response->json();
    }

    private function generateSignature($data)
    {
        return hash_hmac('sha256', json_encode($data), config('services.doku.secret_key'));
    }
}
