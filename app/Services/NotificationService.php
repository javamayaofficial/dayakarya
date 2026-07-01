<?php

namespace App\Services;

use App\Models\User;
use App\Services\Email\EmailManager;
use App\Services\WhatsApp\WhatsAppManager;

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
        if (! $user->phone) {
            return false;
        }
        return WhatsAppManager::driver()->send($user->phone, $message);
    }

    public function email(User $user, string $subject, string $htmlBody): bool
    {
        return EmailManager::driver()->send($user->email, $subject, $htmlBody);
    }

    // ---- Helper event siap pakai ----

    public function topupSuccess(User $user, int $credit): void
    {
        $msg = "Halo {$user->name}! Top up berhasil. {$credit} Credit sudah masuk ke Wallet Dayakarya kamu. Selamat menikmati karya favoritmu!";
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
