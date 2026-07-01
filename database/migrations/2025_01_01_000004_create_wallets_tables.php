<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('credit_balance')->default(0);  // saldo Credit
            $table->unsignedBigInteger('rupiah_balance')->default(0);  // saldo Rupiah (royalti/komisi siap withdraw)
            $table->timestamps();

            $table->unique('user_id');
        });

        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['topup', 'unlock', 'royalty', 'commission', 'withdraw', 'refund', 'adjustment']);
            $table->enum('currency', ['credit', 'rupiah'])->default('credit');
            $table->bigInteger('amount'); // positif = masuk, negatif = keluar
            $table->bigInteger('balance_after')->nullable();
            $table->string('reference')->nullable(); // relasi ke payment/unlock/withdrawal
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['wallet_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
        Schema::dropIfExists('wallets');
    }
};
