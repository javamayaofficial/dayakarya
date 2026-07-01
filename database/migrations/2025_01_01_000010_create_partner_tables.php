<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->enum('type', ['sponsor', 'csr'])->default('sponsor');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['csr', 'sponsorship', 'kompetisi', 'literasi'])->default('csr');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('budget')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->timestamps();
        });

        Schema::create('impact_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('creators_helped')->default(0);
            $table->unsignedBigInteger('works_funded')->default(0);
            $table->unsignedBigInteger('readers_reached')->default(0);
            $table->unsignedBigInteger('funds_distributed')->default(0);
            $table->text('summary')->nullable();
            $table->date('period')->nullable();
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('image');
            $table->string('link')->nullable();
            $table->string('position')->default('home'); // home | explore
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('impact_reports');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('sponsors');
    }
};
