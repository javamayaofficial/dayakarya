<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Provider utama Dayakarya: Duitku.
 * Docs: https://docs.duitku.com
 */
class DuitkuService implements PaymentGateway
{
    protected string $merchantCode;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = (string) config('duitku.merchant_code');
        $this->apiKey       = (string) config('duitku.api_key');
        $this->baseUrl      = (string) config('duitku.base_url');
    }

    public function createTransaction(Payment $payment): array
    {
        // Signature Duitku (Create Invoice): md5(merchantCode + merchantOrderId + amount + apiKey)
        $signature = md5($this->merchantCode . $payment->order_id . $payment->amount_rupiah . $this->apiKey);

        $body = [
            'merchantCode'     => $this->merchantCode,
            'paymentAmount'    => $payment->amount_rupiah,
            'merchantOrderId'  => $payment->order_id,
            'productDetails'   => 'Top Up Credit Dayakarya',
            'email'            => $payment->user->email,
            'customerVaName'   => $payment->user->name,
            'callbackUrl'      => config('duitku.callback_url'),
            'returnUrl'        => config('duitku.return_url'),
            'signature'        => $signature,
            'expiryPeriod'     => 60, // menit
        ];

        try {
            $res = Http::acceptJson()
                ->post($this->baseUrl . '/webapi/api/merchant/v2/inquiry', $body)
                ->throw()
                ->json();

            return [
                'payment_url' => $res['paymentUrl'] ?? null,
                'reference'   => $res['reference'] ?? null,
                'raw'         => $res,
            ];
        } catch (\Throwable $e) {
            Log::error('Duitku createTransaction gagal', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function verifyCallback(array $payload): bool
    {
        // Signature callback Duitku: md5(merchantCode + amount + merchantOrderId + apiKey)
        $expected = md5(
            ($payload['merchantCode'] ?? '') .
            ($payload['amount'] ?? '') .
            ($payload['merchantOrderId'] ?? '') .
            $this->apiKey
        );

        $valid = hash_equals($expected, $payload['signature'] ?? '');
        $success = ($payload['resultCode'] ?? '') === '00'; // 00 = sukses

        return $valid && $success;
    }

    public function extractOrderId(array $payload): ?string
    {
        return $payload['merchantOrderId'] ?? null;
    }
}
