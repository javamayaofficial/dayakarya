<?php

return [
    'token'      => env('MAILKETING_API_TOKEN'),
    'url'        => env('MAILKETING_URL', 'https://api.mailketing.co.id/api/v1/send'),
    'from_email' => env('MAILKETING_FROM_EMAIL', 'noreply@dayakarya.id'),
    'from_name'  => env('MAILKETING_FROM_NAME', 'Dayakarya'),
];
