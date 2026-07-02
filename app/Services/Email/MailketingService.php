<?php

namespace App\Services\Email;

use App\Support\IntegrationSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Provider utama Email Dayakarya: Mailketing.
 */
class MailketingService implements EmailSender
{
    public function send(string $to, string $subject, string $htmlBody): bool
    {
        try {
            $res = Http::asForm()->post(config('mailketing.url'), [
                'api_token'  => config('mailketing.token'),
                'from_name'  => IntegrationSettings::get('mail.from_name', config('dayakarya.mail.from_name', config('mailketing.from_name'))),
                'from_email' => IntegrationSettings::get('mail.from_email', config('dayakarya.mail.from_email', config('mailketing.from_email'))),
                'recipient'  => $to,
                'subject'    => $subject,
                'content'    => $htmlBody,
            ]);

            return $res->successful();
        } catch (\Throwable $e) {
            Log::warning('Mailketing gagal kirim email', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
