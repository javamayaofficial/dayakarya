@extends('layouts.app')
@section('title', 'Transfer Manual — Dayakarya')
@section('body_class', 'page-support')
@section('content')
@php
    /** @var \App\Models\Payment $payment */
    $bankName = \App\Support\IntegrationSettings::get('payment.manual.bank_name', config('dayakarya.manual_payment.bank_name'));
    $bankAccount = \App\Support\IntegrationSettings::get('payment.manual.account', config('dayakarya.manual_payment.account'));
    $bankHolder = \App\Support\IntegrationSettings::get('payment.manual.holder', config('dayakarya.manual_payment.holder'));
    $supportNumber = preg_replace('/\D+/', '', \App\Support\IntegrationSettings::get('support.whatsapp_number', config('dayakarya.support.whatsapp_number')));
    $qrisImage = \App\Support\IntegrationSettings::get('payment.manual.qris_image_url', config('dayakarya.manual_payment.qris_image'));
    $prefilledMessage = rawurlencode(
        'Halo admin, saya sudah transfer top up Credit Dayakarya. '
        . 'Order ID: ' . $payment->order_id
        . ', nominal: Rp' . number_format($payment->amount_rupiah, 0, ',', '.')
        . '.'
    );
@endphp
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel card">
            <span class="section-kicker">Transfer Manual</span>
            <h1 class="support-title">Selesaikan pembayaran dengan langkah yang jelas dan mudah diverifikasi.</h1>
            <div class="alert alert-success">Transfer ke rekening berikut, lalu kirimkan bukti pembayaran. Tim admin akan memverifikasi agar credit dapat diproses dengan aman dan akurat.</div>
            <div class="bank-card">
                <div><strong>Order ID</strong><span>{{ $payment->order_id }}</span></div>
                <div><strong>Total Transfer</strong><span>Rp{{ number_format($payment->amount_rupiah, 0, ',', '.') }}</span></div>
                <div><strong>Credit</strong><span>{{ number_format($payment->credit_amount, 0, ',', '.') }} Credit</span></div>
            </div>
            <div class="bank-card">
                <div><strong>Bank</strong><span>{{ $bankName }}</span></div>
                <div><strong>No. Rekening</strong><span>{{ $bankAccount }}</span></div>
                <div><strong>Atas Nama</strong><span>{{ $bankHolder }}</span></div>
            </div>
            @if ($qrisImage)
                <div class="bank-card">
                    <div><strong>QRIS</strong><span>Gunakan QRIS berikut jika metode pembayaran Anda memakai scan QR.</span></div>
                    <div><a href="{{ $qrisImage }}" target="_blank" rel="noopener">Buka gambar QRIS</a></div>
                </div>
            @endif
            <div class="support-copy">Saat mengirim bukti pembayaran, sertakan order ID agar verifikasi admin lebih cepat dan akurat.</div>
            <a href="https://wa.me/{{ $supportNumber }}?text={{ $prefilledMessage }}" class="btn btn-wa btn-block btn-gap-top">Kirim Bukti via WhatsApp</a>
        </div>
    </div>
</section>
@endsection
