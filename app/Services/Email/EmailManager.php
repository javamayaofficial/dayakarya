<?php

namespace App\Services\Email;

use App\Support\IntegrationSettings;
use InvalidArgumentException;

class EmailManager
{
    public static function driver(?string $provider = null): EmailSender
    {
        $provider ??= IntegrationSettings::get('providers.email', config('dayakarya.providers.email', 'mailketing'));

        return match ($provider) {
            'mailketing' => new MailketingService(),
            // 'kirimemail' => new KirimEmailService(), // alternatif resmi
            default => throw new InvalidArgumentException("Email provider [$provider] tidak dikenal."),
        };
    }
}
