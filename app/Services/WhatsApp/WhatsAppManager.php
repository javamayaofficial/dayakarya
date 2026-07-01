<?php

namespace App\Services\WhatsApp;

use InvalidArgumentException;

class WhatsAppManager
{
    public static function driver(?string $provider = null): WhatsAppSender
    {
        $provider ??= config('dayakarya.providers.whatsapp', 'fonnte');

        return match ($provider) {
            'fonnte' => new FonnteService(),
            // 'onesender' => new OneSenderService(),   // alternatif resmi
            // 'starsender' => new StarSenderService(), // alternatif resmi
            default => throw new InvalidArgumentException("WhatsApp provider [$provider] tidak dikenal."),
        };
    }
}
