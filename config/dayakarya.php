<?php

/**
 * Konfigurasi utama ekosistem Dayakarya.
 * Semua provider integrasi & parameter ekonomi dipusatkan di sini,
 * sehingga bisa diganti cukup lewat .env tanpa mengubah kode.
 */
return [

    // ---- Pemilihan provider (satu utama per kategori) ----
    'providers' => [
        'payment'  => env('PAYMENT_PROVIDER', 'duitku'),   // duitku | midtrans | xendit | manual
        'whatsapp' => env('WA_PROVIDER', 'fonnte'),         // fonnte | onesender | starsender
        'email'    => env('EMAIL_PROVIDER', 'mailketing'),  // mailketing | kirimemail
    ],

    'support' => [
        'whatsapp_number' => env('SUPPORT_WHATSAPP_NUMBER', '62800000000'),
        'email'           => env('SUPPORT_EMAIL', 'admin@dayakarya.id'),
    ],

    'mail' => [
        'from_name'  => env('MAILKETING_FROM_NAME', 'Dayakarya'),
        'from_email' => env('MAILKETING_FROM_EMAIL', 'noreply@dayakarya.id'),
    ],

    'manual_payment' => [
        'bank_name'  => env('MANUAL_BANK_NAME'),
        'account'    => env('MANUAL_BANK_ACCOUNT'),
        'holder'     => env('MANUAL_BANK_HOLDER'),
        'qris_image' => env('MANUAL_QRIS_IMAGE_URL'),
    ],

    // ---- Ekonomi platform ----
    'economy' => [
        'credit_rate_rupiah'          => (int) env('CREDIT_RATE_RUPIAH', 100),
        'royalty_creator_percent'     => (int) env('ROYALTY_CREATOR_PERCENT', 70),
        'affiliate_commission_percent'=> (int) env('AFFILIATE_COMMISSION_PERCENT', 10),
        'withdraw_minimum'            => (int) env('WITHDRAW_MINIMUM', 50000),
        'withdraw_fee'                => (int) env('WITHDRAW_FEE', 2500),
    ],

    // ---- Tipe karya yang didukung ----
    'work_types' => [
        'cerpen'     => 'Cerpen',
        'novel'      => 'Novel Berseri',
        'podcast'    => 'Podcast',
        'audio_story'=> 'Audio Story',
        'dongeng'    => 'Dongeng',
        'motivasi'   => 'Cerita Motivasi',
        'audiobook'  => 'Audiobook',
    ],

    // Tipe berbasis audio (butuh upload audio, bukan teks)
    'audio_types' => ['podcast', 'audio_story', 'dongeng', 'audiobook'],
];
