@extends('layouts.app')
@section('title', 'Transfer Manual — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel card">
            <span class="section-kicker">Transfer Manual</span>
            <h1 class="support-title">Selesaikan pembayaran dengan langkah yang jelas dan mudah diverifikasi.</h1>
            <div class="alert alert-success">Transfer ke rekening berikut, lalu kirimkan bukti pembayaran. Tim admin akan memverifikasi agar credit dapat diproses dengan aman dan akurat.</div>
            <div class="bank-card">
                <div><strong>Bank</strong><span>{{ env('MANUAL_BANK_NAME') }}</span></div>
                <div><strong>No. Rekening</strong><span>{{ env('MANUAL_BANK_ACCOUNT') }}</span></div>
                <div><strong>Atas Nama</strong><span>{{ env('MANUAL_BANK_HOLDER') }}</span></div>
            </div>
            <a href="https://wa.me/62800000000?text=Halo%20admin%2C%20saya%20sudah%20transfer%20top%20up%20Credit%20Dayakarya" class="btn btn-wa btn-block btn-gap-top">Kirim Bukti via WhatsApp</a>
        </div>
    </div>
</section>
@endsection
