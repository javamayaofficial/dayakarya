<?php

namespace App\Services\WhatsApp;

use App\Support\IntegrationSettings;
use InvalidArgumentException;

class WhatsAppManager
{
    public static function driver(?string $provider = null): WhatsAppSender
    {
        $provider ??= IntegrationSettings::get('providers.whatsapp', config('dayakarya.providers.whatsapp', 'fonnte'));

        return match ($provider) {
            'fonnte' => new FonnteService(),
            // 'onesender' => new OneSenderService(),   // alternatif resmi
            // 'starsender' => new StarSenderService(), // alternatif resmi
            default => throw new InvalidArgumentException("WhatsApp provider [$provider] tidak dikenal."),
        };
    }
}
