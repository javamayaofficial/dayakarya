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
            // #region debug-point mailketing-send-entry
            Log::info('Debug Mailketing send entry', [
                'url' => config('mailketing.url'),
                'token_present' => filled(config('mailketing.token')),
                'from_email' => IntegrationSettings::get('mail.from_email', config('dayakarya.mail.from_email', config('mailketing.from_email'))),
                'to' => $to,
                'subject' => $subject,
            ]);
            // #endregion debug-point mailketing-send-entry
            $res = Http::asForm()->post(config('mailketing.url'), [
                'api_token'  => config('mailketing.token'),
                'from_name'  => IntegrationSettings::get('mail.from_name', config('dayakarya.mail.from_name', config('mailketing.from_name'))),
                'from_email' => IntegrationSettings::get('mail.from_email', config('dayakarya.mail.from_email', config('mailketing.from_email'))),
                'recipient'  => $to,
                'subject'    => $subject,
                'content'    => $htmlBody,
            ]);

            // #region debug-point mailketing-send-result
            Log::info('Debug Mailketing send result', [
                'successful' => $res->successful(),
                'status' => $res->status(),
                'body' => $res->body(),
            ]);
            // #endregion debug-point mailketing-send-result
            return $res->successful();
        } catch (\Throwable $e) {
            Log::warning('Mailketing gagal kirim email', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
