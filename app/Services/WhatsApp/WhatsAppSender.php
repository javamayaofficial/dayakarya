<?php

namespace App\Services\WhatsApp;

interface WhatsAppSender
{
    /** Kirim pesan WhatsApp ke nomor tujuan. */
    public function send(string $phone, string $message): bool;
}
