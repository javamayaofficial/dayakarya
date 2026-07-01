<?php

namespace App\Services;

use App\Models\User;
use App\Services\Email\EmailManager;
use App\Services\WhatsApp\WhatsAppManager;
use Illuminate\Support\Facades\Log;

/**
 * NotificationService — satu pintu untuk mengirim notifikasi ke pengguna
 * lewat WhatsApp (Fonnte) dan Email (Mailketing). Provider diatur di config.
 *
 * Trigger utama yang dipakai di seluruh aplikasi:
 *  - Verifikasi akun (register)
 *  - Top up berhasil
 *  - Royalti masuk
 *  - Status withdraw
 *  - Karya baru dari creator yang difollow
 */
class NotificationService
{
    public function whatsapp(User $user, string $message): bool
    {
        // #region debug-point notification-whatsapp-entry
        Log::info('Debug notification whatsapp entry', [
            'user_id' => $user->id,
            'has_phone' => filled($user->phone),
            'phone' => $user->phone,
        ]);
        // #endregion debug-point notification-whatsapp-entry
        if (! $user->phone) {
            // #region debug-point notification-whatsapp-skip
            Log::warning('Debug notification whatsapp skipped because phone is empty', [
                'user_id' => $user->id,
            ]);
            // #endregion debug-point notification-whatsapp-skip
            return false;
        }
        $result = WhatsAppManager::driver()->send($user->phone, $message);
        // #region debug-point notification-whatsapp-result
        Log::info('Debug notification whatsapp result', [
            'user_id' => $user->id,
            'result' => $result,
        ]);
        // #endregion debug-point notification-whatsapp-result
        return $result;
    }

    public function email(User $user, string $subject, string $htmlBody): bool
    {
        // #region debug-point notification-email-entry
        Log::info('Debug notification email entry', [
            'user_id' => $user->id,
            'email' => $user->email,
            'subject' => $subject,
        ]);
        // #endregion debug-point notification-email-entry
        $result = EmailManager::driver()->send($user->email, $subject, $htmlBody);
        // #region debug-point notification-email-result
        Log::info('Debug notification email result', [
            'user_id' => $user->id,
            'result' => $result,
        ]);
        // #endregion debug-point notification-email-result
        return $result;
    }

    // ---- Helper event siap pakai ----

    public function topupSuccess(User $user, int $credit): void
    {
        $msg = "Halo {$user->name}! Top up berhasil. {$credit} Credit sudah masuk ke Wallet Dayakarya kamu. Selamat menikmati karya favoritmu!";
        // #region debug-point notification-topup-success
        Log::info('Debug topupSuccess called', [
            'user_id' => $user->id,
            'credit' => $credit,
        ]);
        // #endregion debug-point notification-topup-success
        $this->whatsapp($user, $msg);
        $this->email(
            $user,
            'Top up Dayakarya berhasil',
            "<p>Halo {$user->name},</p><p>Top up Anda berhasil diproses. <strong>{$credit} Credit</strong> sudah masuk ke Wallet Dayakarya.</p><p>Silakan lanjut menikmati karya favorit Anda di Dayakarya.</p>"
        );
    }

    public function royaltyReceived(User $creator, int $amount, string $workTitle): void
    {
        $rp = 'Rp' . number_format($amount, 0, ',', '.');
        $msg = "Selamat {$creator->name}! Kamu baru saja menerima royalti {$rp} dari karya \"{$workTitle}\". Terus berkarya!";
        $this->whatsapp($creator, $msg);
    }

    public function withdrawStatus(User $user, string $status, int $amount): void
    {
        $rp = 'Rp' . number_format($amount, 0, ',', '.');
        $label = match ($status) {
            'approved' => 'sedang diproses',
            'paid'     => 'telah dikirim ke rekeningmu',
            'rejected' => 'ditolak, silakan cek kembali data rekeningmu',
            default    => 'diterima',
        };
        $this->whatsapp($user, "Penarikan dana {$rp} kamu {$label}.");
    }
}
