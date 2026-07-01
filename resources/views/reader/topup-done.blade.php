@extends('layouts.app')
@section('content')
<section class="section"><div class="container" style="max-width:420px">
<div class="state"><div class="emoji">🎉</div><h3>Pembayaran diproses</h3>
<p>Credit akan otomatis masuk begitu pembayaran terkonfirmasi. Kamu akan menerima notifikasi WhatsApp.</p>
<a href="{{ route('wallet') }}" class="btn btn-primary">Kembali ke Wallet</a></div>
</div></section>
@endsection
