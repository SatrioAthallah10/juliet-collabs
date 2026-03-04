<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Middleware untuk mencatat SEMUA request yang masuk ke endpoint webhook DOKU.
 * 
 * Middleware ini berjalan SEBELUM controller, sehingga:
 * - Request yang ditolak oleh middleware lain tetap tercatat
 * - Raw body payload selalu tercatat meskipun controller throw exception
 * - Response HTTP status code juga tercatat setelah controller selesai
 * 
 * Log ditulis ke channel 'webhook' → storage/logs/webhook.log
 */
class LogDokuWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        // ─── LOG REQUEST MASUK ───
        Log::channel('webhook')->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        Log::channel('webhook')->info('[WEBHOOK REQUEST] 📩 REQUEST MASUK KE ENDPOINT WEBHOOK DOKU');
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Timestamp: ' . now()->toDateTimeString());
        Log::channel('webhook')->info('[WEBHOOK REQUEST] HTTP Method: ' . $request->method());
        Log::channel('webhook')->info('[WEBHOOK REQUEST] URL: ' . $request->fullUrl());
        Log::channel('webhook')->info('[WEBHOOK REQUEST] IP Address: ' . $request->ip());
        Log::channel('webhook')->info('[WEBHOOK REQUEST] User-Agent: ' . ($request->userAgent() ?? 'N/A'));
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Content-Type: ' . ($request->header('Content-Type') ?? 'N/A'));
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Content-Length: ' . ($request->header('Content-Length') ?? 'N/A'));

        // Log semua headers DOKU
        Log::channel('webhook')->info('[WEBHOOK REQUEST] ─── HEADERS ───');
        $dokuHeaders = [
            'Client-Id' => $request->header('Client-Id'),
            'Request-Id' => $request->header('Request-Id'),
            'Request-Timestamp' => $request->header('Request-Timestamp'),
            'Signature' => $request->header('Signature'),
            'X-Signature' => $request->header('X-Signature'),
        ];
        foreach ($dokuHeaders as $key => $value) {
            if ($value) {
                Log::channel('webhook')->info("[WEBHOOK REQUEST] Header [{$key}]: {$value}");
            }
        }

        // Log raw body
        Log::channel('webhook')->info('[WEBHOOK REQUEST] ─── RAW BODY ───');
        $rawBody = $request->getContent();
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Raw Body Length: ' . strlen($rawBody) . ' bytes');
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Raw Body: ' . $rawBody);

        // Log parsed JSON body
        Log::channel('webhook')->info('[WEBHOOK REQUEST] ─── PARSED BODY ───');
        Log::channel('webhook')->info('[WEBHOOK REQUEST] Parsed Body: ' . json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        Log::channel('webhook')->info('[WEBHOOK REQUEST] ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // ─── JALANKAN CONTROLLER ───
        $response = $next($request);

        // ─── LOG RESPONSE KELUAR ───
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        Log::channel('webhook')->info('[WEBHOOK RESPONSE] ─── RESPONSE ───');
        Log::channel('webhook')->info('[WEBHOOK RESPONSE] HTTP Status Code: ' . $response->getStatusCode());
        Log::channel('webhook')->info('[WEBHOOK RESPONSE] Response Body: ' . $response->getContent());
        Log::channel('webhook')->info('[WEBHOOK RESPONSE] Durasi Proses: ' . $duration . ' ms');
        Log::channel('webhook')->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        return $response;
    }
}
