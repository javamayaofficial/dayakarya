<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key'       => env('DUITKU_API_KEY'),
    'env'           => env('DUITKU_ENV', 'sandbox'),
    'base_url'      => env('DUITKU_ENV', 'sandbox') === 'production'
        ? 'https://passport.duitku.com'
        : 'https://sandbox.duitku.com',
    'callback_url'  => env('DUITKU_CALLBACK_URL'),
    'return_url'    => env('DUITKU_RETURN_URL'),
];
