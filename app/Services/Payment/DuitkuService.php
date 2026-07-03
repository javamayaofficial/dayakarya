<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

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
        $paymentMethod = $payment->payment_method ?: $this->resolvePaymentMethod($payment->amount_rupiah);

        // Signature Duitku (Create Invoice): md5(merchantCode + merchantOrderId + amount + apiKey)
        $signature = md5($this->merchantCode . $payment->order_id . $payment->amount_rupiah . $this->apiKey);

        $body = [
            'merchantCode'     => $this->merchantCode,
            'paymentAmount'    => $payment->amount_rupiah,
            'paymentMethod'    => $paymentMethod,
            'merchantOrderId'  => $payment->order_id,
            'productDetails'   => 'Top Up Credit Dayakarya',
            'email'            => $payment->user->email,
            'customerVaName'   => $payment->user->name,
            'phoneNumber'      => $payment->user->phone,
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
                'payment_method' => $paymentMethod,
                'raw'         => $res,
            ];
        } catch (\Throwable $e) {
            Log::error('Duitku createTransaction gagal', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getPaymentMethods(int $amount): array
    {
        $datetime = now()->format('Y-m-d H:i:s');
        $signature = hash('sha256', $this->merchantCode . $amount . $datetime . $this->apiKey);

        $body = [
            'merchantcode' => $this->merchantCode,
            'amount' => $amount,
            'datetime' => $datetime,
            'signature' => $signature,
        ];

        try {
            $res = Http::acceptJson()
                ->post($this->baseUrl . '/webapi/api/merchant/paymentmethod/getpaymentmethod', $body)
                ->throw()
                ->json();

            $methods = collect($res['paymentFee'] ?? [])
                ->filter(fn ($item) => filled($item['paymentMethod'] ?? null))
                ->map(fn ($item) => [
                    'code' => (string) $item['paymentMethod'],
                    'name' => (string) ($item['paymentName'] ?? $item['paymentMethod']),
                    'image' => $item['paymentImage'] ?? null,
                    'fee' => (int) ($item['totalFee'] ?? 0),
                ])
                ->values()
                ->all();

            if (empty($methods)) {
                throw new RuntimeException('Duitku tidak mengembalikan metode pembayaran aktif.');
            }

            return $methods;
        } catch (\Throwable $e) {
            Log::error('Duitku getPaymentMethods gagal', ['error' => $e->getMessage(), 'amount' => $amount]);
            throw $e;
        }
    }

    protected function resolvePaymentMethod(int $amount): string
    {
        $methods = $this->getPaymentMethods($amount);
        $preferredOrder = ['SP', 'BC', 'BR', 'M2', 'I1', 'BT', 'VA', 'DA', 'OV', 'SA', 'VC'];

        usort($methods, function (array $left, array $right) use ($preferredOrder) {
            $leftIndex = array_search($left['code'], $preferredOrder, true);
            $rightIndex = array_search($right['code'], $preferredOrder, true);

            $leftRank = $leftIndex === false ? 999 : $leftIndex;
            $rightRank = $rightIndex === false ? 999 : $rightIndex;

            return $leftRank <=> $rightRank;
        });

        return (string) ($methods[0]['code'] ?? '');
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
