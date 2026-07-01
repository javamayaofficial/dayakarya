<?php

namespace App\Services\Payment;

use App\Models\Payment;

/**
 * Kontrak universal payment gateway.
 * Semua provider (Duitku, Midtrans, Xendit, Manual) wajib mengikuti bentuk ini,
 * sehingga bisa ditukar cukup lewat config('dayakarya.providers.payment').
 */
interface PaymentGateway
{
    /**
     * Buat transaksi pembayaran.
     * @return array{payment_url:?string, reference:?string, raw:array}
     */
    public function createTransaction(Payment $payment): array;

    /**
     * Verifikasi callback/webhook dari provider.
     * Return true bila pembayaran sah & sukses.
     */
    public function verifyCallback(array $payload): bool;

    /**
     * Ambil order_id internal dari payload callback.
     */
    public function extractOrderId(array $payload): ?string;
}
