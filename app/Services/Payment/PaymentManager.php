<?php

namespace App\Services\Payment;

use App\Support\IntegrationSettings;
use InvalidArgumentException;

/**
 * Memilih implementasi payment sesuai config('dayakarya.providers.payment').
 * Ganti provider cukup lewat .env (PAYMENT_PROVIDER=duitku|manual|...).
 */
class PaymentManager
{
    public static function driver(?string $provider = null): PaymentGateway
    {
        $provider ??= IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment', 'duitku'));

        return match ($provider) {
            'duitku' => new DuitkuService(),
            'manual', 'qris_manual' => new ManualPaymentService(),
            // 'midtrans' => new MidtransService(),  // alternatif (siapkan bila perlu)
            // 'xendit'   => new XenditService(),    // alternatif (siapkan bila perlu)
            default => throw new InvalidArgumentException("Payment provider [$provider] tidak dikenal."),
        };
    }
}
