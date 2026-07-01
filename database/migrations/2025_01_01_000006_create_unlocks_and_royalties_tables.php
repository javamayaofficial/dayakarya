<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('affiliate_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('credit_spent');
            $table->timestamps();

            $table->unique(['user_id', 'chapter_id']); // cegah double-unlock
        });

        Schema::create('royalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unlock_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount_rupiah');
            $table->timestamps();

            $table->index('creator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('royalties');
        Schema::dropIfExists('unlocks');
    }
};
