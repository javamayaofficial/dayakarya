@extends('layouts.app')
@section('title', 'Pembayaran Diproses — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel state state-panel">
            <div class="emoji">🎉</div>
            <h3>Pembayaran sedang diproses</h3>
            <p>Setelah terkonfirmasi, credit akan masuk otomatis ke wallet Anda.</p>
            <a href="{{ route('wallet') }}" class="btn btn-primary">Kembali ke Wallet</a>
        </div>
    </div>
</section>
@endsection
