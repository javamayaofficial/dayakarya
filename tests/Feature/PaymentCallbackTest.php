<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'duitku.merchant_code' => 'DTEST1',
            'duitku.api_key' => 'secret-callback-key',
        ]);

        $this->app->instance(NotificationService::class, new class extends NotificationService {
            public function topupSuccess(\App\Models\User $user, int $credit): void
            {
            }
        });
    }

    public function test_success_callback_marks_payment_paid_and_credits_wallet_once(): void
    {
        $user = $this->createUser([
            'email' => 'callback-success@example.test',
        ]);

        $payment = $this->createPayment($user, [
            'order_id' => 'DK-CALLBACK-OK',
            'amount_rupiah' => 30000,
            'credit_amount' => 300,
            'status' => 'pending',
        ]);

        $payload = $this->callbackPayload($payment, [
            'resultCode' => '00',
            'reference' => 'DUITKU-REF-OK',
            'paymentCode' => 'QR',
        ]);

        $this->post('/api/v1/payments/duitku/callback', $payload)
            ->assertOk();

        $this->post('/api/v1/payments/duitku/callback', $payload)
            ->assertOk();

        $payment->refresh();
        $user->wallet->refresh();

        $this->assertSame('paid', $payment->status);
        $this->assertSame('DUITKU-REF-OK', $payment->reference);
        $this->assertSame('QR', $payment->payment_method);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame(300, $user->wallet->credit_balance);
    }

    public function test_expired_callback_marks_payment_expired_without_crediting_wallet(): void
    {
        $user = $this->createUser([
            'email' => 'callback-expired@example.test',
        ]);

        $payment = $this->createPayment($user, [
            'order_id' => 'DK-CALLBACK-EXP',
            'amount_rupiah' => 45000,
            'credit_amount' => 450,
            'status' => 'pending',
        ]);

        $payload = $this->callbackPayload($payment, [
            'resultCode' => '02',
            'reference' => 'DUITKU-REF-EXP',
            'paymentCode' => 'VA',
        ]);

        $this->post('/api/v1/payments/duitku/callback', $payload)
            ->assertOk();

        $payment->refresh();
        $user->wallet->refresh();

        $this->assertSame('expired', $payment->status);
        $this->assertNull($payment->paid_at);
        $this->assertSame(0, $user->wallet->credit_balance);
    }

    public function test_failed_callback_marks_payment_failed_without_crediting_wallet(): void
    {
        $user = $this->createUser([
            'email' => 'callback-failed@example.test',
        ]);

        $payment = $this->createPayment($user, [
            'order_id' => 'DK-CALLBACK-FAIL',
            'amount_rupiah' => 50000,
            'credit_amount' => 500,
            'status' => 'pending',
        ]);

        $payload = $this->callbackPayload($payment, [
            'resultCode' => '99',
            'reference' => 'DUITKU-REF-FAIL',
        ]);

        $this->post('/api/v1/payments/duitku/callback', $payload)
            ->assertOk();

        $payment->refresh();
        $user->wallet->refresh();

        $this->assertSame('failed', $payment->status);
        $this->assertNull($payment->paid_at);
        $this->assertSame(0, $user->wallet->credit_balance);
    }

    public function test_invalid_signature_is_rejected_and_payment_stays_pending(): void
    {
        $user = $this->createUser([
            'email' => 'callback-invalid@example.test',
        ]);

        $payment = $this->createPayment($user, [
            'order_id' => 'DK-CALLBACK-BAD',
            'amount_rupiah' => 25000,
            'credit_amount' => 250,
            'status' => 'pending',
        ]);

        $payload = $this->callbackPayload($payment, [
            'resultCode' => '00',
            'signature' => 'invalid-signature',
        ]);

        $this->post('/api/v1/payments/duitku/callback', $payload)
            ->assertStatus(400);

        $payment->refresh();
        $user->wallet->refresh();

        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->paid_at);
        $this->assertSame(0, $user->wallet->credit_balance);
    }

    protected function callbackPayload(Payment $payment, array $overrides = []): array
    {
        $payload = array_merge([
            'merchantCode' => (string) config('duitku.merchant_code'),
            'amount' => (string) $payment->amount_rupiah,
            'merchantOrderId' => $payment->order_id,
            'resultCode' => '00',
            'reference' => 'DUITKU-REF',
            'paymentCode' => 'QR',
        ], $overrides);

        $payload['signature'] = $payload['signature'] ?? md5(
            $payload['merchantCode']
            . $payload['amount']
            . $payload['merchantOrderId']
            . config('duitku.api_key')
        );

        return $payload;
    }

    protected function createPayment(User $user, array $overrides = []): Payment
    {
        return Payment::create(array_merge([
            'user_id' => $user->id,
            'order_id' => 'DK-CALLBACK-TEST',
            'provider' => 'duitku',
            'amount_rupiah' => 10000,
            'credit_amount' => 100,
            'status' => 'pending',
        ], $overrides));
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Callback ' . $sequence,
            'username' => 'user-callback-' . $sequence,
            'email' => 'user-callback-' . $sequence . '@example.test',
            'phone' => '0812300000' . $sequence,
            'password' => 'secret123',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }
}
