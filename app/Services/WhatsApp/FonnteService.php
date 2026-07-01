<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Provider utama WhatsApp Dayakarya: Fonnte.
 * Docs: https://docs.fonnte.com
 */
class FonnteService implements WhatsAppSender
{
    public function send(string $phone, string $message): bool
    {
        try {
            // #region debug-point fonnte-send-entry
            Log::info('Debug Fonnte send entry', [
                'url' => config('fonnte.url'),
                'token_present' => filled(config('fonnte.token')),
                'target' => $this->normalize($phone),
            ]);
            // #endregion debug-point fonnte-send-entry
            $res = Http::withHeaders(['Authorization' => config('fonnte.token')])
                ->asForm()
                ->post(config('fonnte.url'), [
                    'target'  => $this->normalize($phone),
                    'message' => $message,
                ]);

            // #region debug-point fonnte-send-result
            Log::info('Debug Fonnte send result', [
                'successful' => $res->successful(),
                'status' => $res->status(),
                'body' => $res->body(),
            ]);
            // #endregion debug-point fonnte-send-result
            return $res->successful();
        } catch (\Throwable $e) {
            Log::warning('Fonnte gagal kirim WA', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /** 08xx -> 628xx */
    protected function normalize(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
