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
            $res = Http::withHeaders(['Authorization' => config('fonnte.token')])
                ->asForm()
                ->post(config('fonnte.url'), [
                    'target'  => $this->normalize($phone),
                    'message' => $message,
                ]);

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
