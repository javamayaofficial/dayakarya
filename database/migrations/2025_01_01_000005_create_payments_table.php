<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->unique();            // merchantOrderId internal
            $table->string('provider')->default('duitku');   // duitku | manual | qris_manual
            $table->unsignedBigInteger('amount_rupiah');     // nominal bayar
            $table->unsignedBigInteger('credit_amount');     // credit yang didapat
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'awaiting_confirmation'])->default('pending');
            $table->string('reference')->nullable();         // reference dari gateway
            $table->string('payment_method')->nullable();    // VA, QRIS, ewallet, dll
            $table->string('payment_url')->nullable();       // url redirect gateway
            $table->string('proof')->nullable();             // bukti transfer manual
            $table->json('meta')->nullable();                // payload mentah gateway
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
