<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('audio_url');
        });

        DB::statement("
            ALTER TABLE works
            MODIFY type ENUM(
                'cerpen',
                'novel',
                'podcast',
                'audio_story',
                'video_series',
                'dongeng',
                'motivasi',
                'audiobook'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE works
            MODIFY type ENUM(
                'cerpen',
                'novel',
                'podcast',
                'audio_story',
                'dongeng',
                'motivasi',
                'audiobook'
            ) NOT NULL
        ");

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
