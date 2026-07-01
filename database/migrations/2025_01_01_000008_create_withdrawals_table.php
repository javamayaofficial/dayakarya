<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('destination_type', ['bank', 'ewallet']);
            $table->string('destination_name');   // BCA / DANA / OVO dst
            $table->string('account_number');
            $table->string('account_holder');
            $table->unsignedBigInteger('amount');  // jumlah diminta (rupiah)
            $table->unsignedBigInteger('fee')->default(0);
            $table->unsignedBigInteger('net_amount'); // amount - fee
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->string('note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
