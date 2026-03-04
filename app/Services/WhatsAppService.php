<?php

declare(strict_types = 1)
;

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp messaging service using Fonnte API.
 *
 * Fonnte is a popular Indonesian WhatsApp Business API provider.
 * Docs: https://md.fonnte.com/
 *
 * Required .env keys:
 *   FONNTE_API_TOKEN=your_fonnte_token
 */
class WhatsAppService
{
    private string $apiUrl = 'https://api.fonnte.com/send';
    private ?string $apiToken;

    public function __construct()
    {
        $this->apiToken = env('FONNTE_API_TOKEN');
    }

    /**
     * Check if WhatsApp integration is configured and enabled.
     */
    public function isEnabled(): bool
    {
        return !empty($this->apiToken);
    }

    /**
     * Send a text message via WhatsApp.
     *
     * @param string $phone  Recipient phone number (e.g. 08123456789 or 628123456789)
     * @param string $message  The text message to send
     * @return bool  Whether the message was sent successfully
     */
    public function sendTextMessage(string $phone, string $message): bool
    {
        if (!$this->isEnabled()) {
            Log::channel('whatsapp')->warning('[WHATSAPP] API token not configured — message NOT sent', [
                'phone' => $phone,
            ]);
            return false;
        }

        $phone = $this->formatPhoneNumber($phone);

        Log::channel('whatsapp')->info('[WHATSAPP] Sending text message', [
            'phone' => $phone,
            'message_length' => strlen($message),
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ]);

            $responseBody = $response->json();

            if ($response->successful() && ($responseBody['status'] ?? false)) {
                Log::channel('whatsapp')->info('[WHATSAPP] ✅ Message sent successfully', [
                    'phone' => $phone,
                    'response' => $responseBody,
                ]);
                return true;
            }

            Log::channel('whatsapp')->warning('[WHATSAPP] ⚠️ API returned non-success response', [
                'phone' => $phone,
                'http_status' => $response->status(),
                'response' => $responseBody,
            ]);
            return false;

        }
        catch (\Throwable $e) {
            Log::channel('whatsapp')->error('[WHATSAPP] ❌ Exception while sending message', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return false;
        }
    }

    /**
     * Send registration credentials via WhatsApp.
     *
     * @param string $phone       Recipient phone number
     * @param string $schoolName  Name of the school
     * @param string $schoolCode  School code (e.g. SCH20261)
     * @param string $email       Login email address
     * @param string $password    Login password (plaintext or placeholder)
     * @param string $loginUrl    URL to the login page
     * @return bool
     */
    public function sendCredentials(
        string $phone,
        string $schoolName,
        string $schoolCode,
        string $email,
        string $password,
        string $loginUrl
        ): bool
    {
        $message = $this->buildCredentialMessage(
            $schoolName,
            $schoolCode,
            $email,
            $password,
            $loginUrl
        );

        return $this->sendTextMessage($phone, $message);
    }

    /**
     * Build the credential notification message body.
     */
    private function buildCredentialMessage(
        string $schoolName,
        string $schoolCode,
        string $email,
        string $password,
        string $loginUrl
        ): string
    {
        $systemName = env('APP_NAME', 'eSchool SaaS');

        return "🎓 *Selamat Datang di {$systemName}!*\n\n"
            . "Akun sekolah Anda telah berhasil dibuat.\n\n"
            . "📋 *Detail Sekolah:*\n"
            . "• Nama: {$schoolName}\n"
            . "• Kode: {$schoolCode}\n\n"
            . "🔐 *Kredensial Login:*\n"
            . "• Email: {$email}\n"
            . "• Password: {$password}\n\n"
            . "🔗 *Link Login:*\n{$loginUrl}\n\n"
            . "⚠️ _Demi keamanan, segera ubah password Anda setelah login pertama kali._\n\n"
            . "Jika ada pertanyaan, silakan hubungi tim support kami.\n\n"
            . "Terima kasih,\n_{$systemName} Team_";
    }

    /**
     * Format phone number to Indonesian international format.
     * Converts:
     *   08123456789   -> 628123456789
     *   8123456789    -> 628123456789
     *   +628123456789 -> 628123456789
     *   628123456789  -> 628123456789
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading + if somehow still present
        $phone = ltrim($phone, '+');

        // If starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with 62, prepend it
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
