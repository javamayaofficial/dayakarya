<?php

namespace App\Services\Payment;

use App\Models\Payment;

/**
 * Fallback pembayaran: Transfer Manual / QRIS Manual.
 * Dipakai bila gateway online belum aktif atau bermasalah.
 * Pembayaran menunggu konfirmasi admin (status: awaiting_confirmation).
 */
class ManualPaymentService implements PaymentGateway
{
    public function createTransaction(Payment $payment): array
    {
        $payment->update(['status' => 'awaiting_confirmation']);

        $instructions = [
            'bank_name'    => env('MANUAL_BANK_NAME'),
            'account'      => env('MANUAL_BANK_ACCOUNT'),
            'holder'       => env('MANUAL_BANK_HOLDER'),
            'qris_image'   => env('MANUAL_QRIS_IMAGE_URL'),
            'amount'       => $payment->amount_rupiah,
            'note'         => 'Sertakan kode order: ' . $payment->order_id,
        ];

        return [
            'payment_url' => route('wallet.topup.manual', $payment),
            'reference'   => $payment->order_id,
            'raw'         => $instructions,
        ];
    }

    public function verifyCallback(array $payload): bool
    {
        // Verifikasi manual dilakukan admin di panel Filament, bukan otomatis.
        return false;
    }

    public function extractOrderId(array $payload): ?string
    {
        return $payload['order_id'] ?? null;
    }
}
