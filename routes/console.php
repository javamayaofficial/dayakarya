<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\URL;

// Publikasikan chapter terjadwal setiap menit
Schedule::call(function () {
    \App\Models\Chapter::where('status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->update(['status' => 'published', 'published_at' => now()]);
})->everyMinute();

Artisan::command('deploy:opcache-reset-url {--base=}', function () {
    $base = rtrim((string) ($this->option('base') ?: config('app.url')), '/');
    $path = URL::temporarySignedRoute(
        'deploy.opcache-reset',
        now()->addMinutes(5),
        [],
        absolute: false,
    );

    $this->line($base . $path);
})->purpose('Generate signed URL for deploy-time web opcache reset');
