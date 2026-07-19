<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Services\Payment\DuitkuService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook/callback pembayaran dari Duitku.
 * Endpoint ini dipanggil server Duitku, bukan pengguna. Wajib verifikasi signature.
 */
class PaymentCallbackController extends \App\Http\Controllers\Controller
{
    public function __construct(protected WalletService $wallet) {}

    public function duitku(Request $request)
    {
        $payload = $request->all();
        $gateway = new DuitkuService();

        if (! $gateway->verifyCallback($payload)) {
            Log::warning('Callback Duitku tidak valid', $payload);
            return response('Invalid signature', 400);
        }

        $orderId = $gateway->extractOrderId($payload);
        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            return response('Order not found', 404);
        }

        $payment->forceFill([
            'reference' => $payload['reference'] ?? $payment->reference,
            'payment_method' => $payload['paymentCode'] ?? $payment->payment_method,
            'meta' => array_merge($payment->meta ?? [], [
                'duitku_callback' => $payload,
            ]),
        ])->save();

        $status = $gateway->resolveCallbackStatus($payload);

        if ($status === 'paid') {
            // Tambahkan Credit (idempotent). Notifikasi dipicu dari WalletService
            $this->wallet->creditTopup($payment);

            return response('OK', 200);
        }

        if ($payment->status !== 'paid') {
            $payment->forceFill([
                'status' => $status,
                'paid_at' => null,
            ])->save();
        }

        return response('OK', 200);
    }
}
