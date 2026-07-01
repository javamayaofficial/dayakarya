@extends('layouts.app')
@section('title', 'Transfer Manual — Dayakarya')
@section('body_class', 'page-support')
@section('content')
@php
    $bankName = \App\Support\IntegrationSettings::get('payment.manual.bank_name', config('dayakarya.manual_payment.bank_name'));
    $bankAccount = \App\Support\IntegrationSettings::get('payment.manual.account', config('dayakarya.manual_payment.account'));
    $bankHolder = \App\Support\IntegrationSettings::get('payment.manual.holder', config('dayakarya.manual_payment.holder'));
    $supportNumber = preg_replace('/\D+/', '', \App\Support\IntegrationSettings::get('support.whatsapp_number', config('dayakarya.support.whatsapp_number')));
@endphp
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel card">
            <span class="section-kicker">Transfer Manual</span>
            <h1 class="support-title">Selesaikan pembayaran dengan langkah yang jelas dan mudah diverifikasi.</h1>
            <div class="alert alert-success">Transfer ke rekening berikut, lalu kirimkan bukti pembayaran. Tim admin akan memverifikasi agar credit dapat diproses dengan aman dan akurat.</div>
            <div class="bank-card">
                <div><strong>Bank</strong><span>{{ $bankName }}</span></div>
                <div><strong>No. Rekening</strong><span>{{ $bankAccount }}</span></div>
                <div><strong>Atas Nama</strong><span>{{ $bankHolder }}</span></div>
            </div>
            <a href="https://wa.me/{{ $supportNumber }}?text=Halo%20admin%2C%20saya%20sudah%20transfer%20top%20up%20Credit%20Dayakarya" class="btn btn-wa btn-block btn-gap-top">Kirim Bukti via WhatsApp</a>
        </div>
    </div>
</section>
@endsection
