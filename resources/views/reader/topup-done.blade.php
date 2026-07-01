@extends('layouts.app')
@section('title', 'Pembayaran Diproses — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel state state-panel">
            <div class="emoji">🎉</div>
            <h3>Pembayaran sedang diproses</h3>
            <p>Credit akan otomatis masuk begitu pembayaran terkonfirmasi. Anda akan menerima notifikasi WhatsApp setelah transaksi tervalidasi.</p>
            <a href="{{ route('wallet') }}" class="btn btn-primary">Kembali ke Wallet</a>
        </div>
    </div>
</section>
@endsection
