<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\Payment\PaymentManager;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook/callback pembayaran dari Duitku.
 * Endpoint ini dipanggil server Duitku, bukan pengguna. Wajib verifikasi signature.
 */
class PaymentCallbackController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected WalletService $wallet,
        protected NotificationService $notifier
    ) {}

    public function duitku(Request $request)
    {
        $payload = $request->all();
        $gateway = PaymentManager::driver('duitku');

        if (! $gateway->verifyCallback($payload)) {
            Log::warning('Callback Duitku tidak valid', $payload);
            return response('Invalid signature', 400);
        }

        $orderId = $gateway->extractOrderId($payload);
        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            return response('Order not found', 404);
        }

        // Tambahkan Credit (idempotent) + notifikasi hanya saat status benar-benar berubah
        if ($this->wallet->creditTopup($payment)) {
            $this->notifier->topupSuccess($payment->user, $payment->credit_amount);
        }

        return response('OK', 200);
    }
}
