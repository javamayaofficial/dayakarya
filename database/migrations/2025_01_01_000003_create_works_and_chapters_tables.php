<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['cerpen', 'novel', 'podcast', 'audio_story', 'dongeng', 'motivasi', 'audiobook']);
            $table->text('synopsis')->nullable();
            $table->string('cover')->nullable();
            $table->enum('status', ['draft', 'review', 'published', 'rejected'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'type']);
            $table->index('creator_id');
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('order')->default(1);
            $table->longText('content')->nullable();       // untuk karya teks
            $table->string('audio_url')->nullable();        // untuk karya audio
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->unsignedInteger('price_credit')->default(0);
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['work_id', 'order']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('works');
    }
};
