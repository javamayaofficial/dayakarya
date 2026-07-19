<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Services\Payment\PaymentManager;
use App\Services\Payment\DuitkuService;
use App\Support\IntegrationSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Wallet & Top Up Credit. Provider utama: Duitku (config-driven).
 */
class WalletController extends \App\Http\Controllers\Controller
{
    public function show(Request $request): JsonResponse
    {
        $wallet = $request->user()->wallet;
        return response()->json([
            'credit_balance' => $wallet->credit_balance,
            'rupiah_balance' => $wallet->rupiah_balance,
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $trx = $request->user()->wallet
            ->transactions()->latest()->paginate(20);
        return response()->json($trx);
    }

    public function paymentStatus(Request $request, Payment $payment): JsonResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 404);

        return response()->json([
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'provider' => $payment->provider,
            'status' => $payment->status,
            'credit_amount' => (int) $payment->credit_amount,
            'amount_rupiah' => (int) $payment->amount_rupiah,
            'paid_at' => optional($payment->paid_at)?->toIso8601String(),
            'payment_url' => $payment->payment_url,
        ]);
    }

    public function paymentMethods(Request $request): JsonResponse
    {
        $provider = IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment'));

        if ($provider !== 'duitku') {
            return response()->json([
                'provider' => $provider,
                'methods' => [],
            ]);
        }

        $data = $request->validate([
            'credit_amount' => ['required', 'integer', 'min:10'],
        ]);

        $rate = (int) config('dayakarya.economy.credit_rate_rupiah');
        $rupiah = $data['credit_amount'] * $rate;

        try {
            $methods = (new DuitkuService())->getPaymentMethods($rupiah);
        } catch (RequestException $exception) {
            $gatewayMessage = data_get($exception->response?->json(), 'Message')
                ?? data_get($exception->response?->json(), 'message')
                ?? 'Metode pembayaran Duitku belum bisa dimuat. Pastikan project aktif dan channel payment sudah tersedia.';

            return response()->json([
                'message' => $gatewayMessage,
            ], 422);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Metode pembayaran belum berhasil dimuat. Coba lagi sebentar.',
            ], 500);
        }

        return response()->json([
            'provider' => 'duitku',
            'methods' => $methods,
        ]);
    }

    /**
     * Buat transaksi top up. Mengembalikan payment_url (redirect Duitku)
     * atau instruksi transfer manual bila provider = manual (fallback).
     */
    public function topup(Request $request): JsonResponse
    {
        $provider = IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment'));

        $data = $request->validate([
            'credit_amount' => ['required', 'integer', 'min:10'],
            'payment_method' => ['nullable', 'string', 'max:10'],
        ]);

        $rate   = (int) config('dayakarya.economy.credit_rate_rupiah');
        $rupiah = $data['credit_amount'] * $rate;

        $payment = Payment::create([
            'user_id'       => $request->user()->id,
            'order_id'      => 'DK-' . strtoupper(Str::random(10)),
            'provider'      => $provider,
            'amount_rupiah' => $rupiah,
            'credit_amount' => $data['credit_amount'],
            'payment_method'=> $provider === 'duitku' ? ($data['payment_method'] ?? null) : null,
            'status'        => 'pending',
        ]);

        try {
            $result = PaymentManager::driver()->createTransaction($payment);
        } catch (RequestException $exception) {
            $payment->update([
                'status' => 'failed',
                'meta' => [
                    'error' => $exception->getMessage(),
                    'response' => $exception->response?->json(),
                ],
            ]);

            $gatewayMessage = data_get($exception->response?->json(), 'Message')
                ?? data_get($exception->response?->json(), 'message')
                ?? 'Gateway pembayaran belum bisa dipakai. Pastikan project Duitku sudah aktif dan kredensial production sesuai.';

            return response()->json([
                'message' => $gatewayMessage,
            ], 422);
        } catch (Throwable $exception) {
            $payment->update([
                'status' => 'failed',
                'meta' => [
                    'error' => $exception->getMessage(),
                ],
            ]);

            return response()->json([
                'message' => 'Transaksi belum berhasil dibuat. Silakan coba lagi dalam beberapa saat.',
            ], 500);
        }

        $payment->update([
            'reference'   => $result['reference'] ?? null,
            'payment_method' => $result['payment_method'] ?? $payment->payment_method,
            'payment_url' => $result['payment_url'] ?? null,
            'meta'        => $result['raw'] ?? null,
        ]);

        return response()->json([
            'message'     => 'Silakan selesaikan pembayaran.',
            'payment_id'  => $payment->id,
            'order_id'    => $payment->order_id,
            'amount'      => $rupiah,
            'payment_url' => $result['payment_url'] ?? null,
            'instructions'=> $result['raw'] ?? null, // untuk manual/QRIS
        ], 201);
    }

    public function uploadProof(Request $request, Payment $payment): JsonResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 404);

        if (! in_array($payment->provider, ['manual', 'qris_manual'], true)) {
            return response()->json([
                'message' => 'Bukti transfer hanya tersedia untuk pembayaran manual.',
            ], 422);
        }

        if ($payment->status !== 'awaiting_confirmation') {
            return response()->json([
                'message' => 'Transaksi ini tidak lagi menunggu verifikasi bukti transfer.',
            ], 422);
        }

        $data = $request->validate([
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($payment->proof) {
            Storage::disk('public')->delete($payment->proof);
        }

        $extension = $data['proof']->getClientOriginalExtension() ?: 'jpg';
        $path = $data['proof']->storeAs(
            'payment-proofs',
            strtolower($payment->order_id) . '-' . time() . '.' . strtolower($extension),
            'public'
        );

        $payment->update(['proof' => $path]);

        return response()->json([
            'message' => 'Bukti transfer berhasil diunggah. Tim admin akan memverifikasi pembayaran Anda.',
            'proof' => $path,
            'proof_url' => Storage::disk('public')->url($path),
        ]);
    }
}
