<?php

use Illuminate\Support\Facades\Schedule;

// Publikasikan chapter terjadwal setiap menit
Schedule::call(function () {
    \App\Models\Chapter::where('status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->update(['status' => 'published', 'published_at' => now()]);
})->everyMinute();
