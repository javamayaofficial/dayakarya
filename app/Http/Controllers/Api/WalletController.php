<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Services\Payment\PaymentManager;
use App\Support\IntegrationSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
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

    /**
     * Buat transaksi top up. Mengembalikan payment_url (redirect Duitku)
     * atau instruksi transfer manual bila provider = manual (fallback).
     */
    public function topup(Request $request): JsonResponse
    {
        $data = $request->validate([
            'credit_amount' => ['required', 'integer', 'min:10'],
        ]);

        $rate   = (int) config('dayakarya.economy.credit_rate_rupiah');
        $rupiah = $data['credit_amount'] * $rate;

        $payment = Payment::create([
            'user_id'       => $request->user()->id,
            'order_id'      => 'DK-' . strtoupper(Str::random(10)),
            'provider'      => IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment')),
            'amount_rupiah' => $rupiah,
            'credit_amount' => $data['credit_amount'],
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
            'payment_url' => $result['payment_url'] ?? null,
            'meta'        => $result['raw'] ?? null,
        ]);

        return response()->json([
            'message'     => 'Silakan selesaikan pembayaran.',
            'order_id'    => $payment->order_id,
            'amount'      => $rupiah,
            'payment_url' => $result['payment_url'] ?? null,
            'instructions'=> $result['raw'] ?? null, // untuk manual/QRIS
        ], 201);
    }
}
