<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Support\IntegrationSettings;

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
            'bank_name'    => IntegrationSettings::get('payment.manual.bank_name', config('dayakarya.manual_payment.bank_name')),
            'account'      => IntegrationSettings::get('payment.manual.account', config('dayakarya.manual_payment.account')),
            'holder'       => IntegrationSettings::get('payment.manual.holder', config('dayakarya.manual_payment.holder')),
            'qris_image'   => IntegrationSettings::get('payment.manual.qris_image_url', config('dayakarya.manual_payment.qris_image')),
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
