<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_owner_can_read_payment_status(): void
    {
        $owner = $this->createUser([
            'email' => 'payment-owner@example.test',
        ]);

        $paidAt = now()->startOfSecond();

        $payment = Payment::create([
            'user_id' => $owner->id,
            'order_id' => 'DK-OWNER1234',
            'provider' => 'duitku',
            'amount_rupiah' => 50000,
            'credit_amount' => 500,
            'status' => 'paid',
            'payment_method' => 'QRIS',
            'payment_url' => 'https://duitku.example.test/pay/owner',
            'paid_at' => $paidAt,
        ]);

        Sanctum::actingAs($owner);

        $this->getJson("/api/v1/payments/{$payment->id}")
            ->assertOk()
            ->assertJsonPath('payment_id', $payment->id)
            ->assertJsonPath('order_id', 'DK-OWNER1234')
            ->assertJsonPath('provider', 'duitku')
            ->assertJsonPath('status', 'paid')
            ->assertJsonPath('credit_amount', 500)
            ->assertJsonPath('amount_rupiah', 50000)
            ->assertJsonPath('payment_url', 'https://duitku.example.test/pay/owner')
            ->assertJsonPath('paid_at', $paidAt->toIso8601String());
    }

    public function test_payment_status_returns_not_found_for_other_user(): void
    {
        $owner = $this->createUser([
            'email' => 'payment-owner-2@example.test',
        ]);

        $otherUser = $this->createUser([
            'email' => 'payment-other@example.test',
        ]);

        $payment = Payment::create([
            'user_id' => $owner->id,
            'order_id' => 'DK-OWNER5678',
            'provider' => 'duitku',
            'amount_rupiah' => 25000,
            'credit_amount' => 250,
            'status' => 'pending',
        ]);

        Sanctum::actingAs($otherUser);

        $this->getJson("/api/v1/payments/{$payment->id}")
            ->assertNotFound();
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Payment ' . $sequence,
            'username' => 'user-payment-' . $sequence,
            'email' => 'user-payment-' . $sequence . '@example.test',
            'password' => 'secret123',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }
}
