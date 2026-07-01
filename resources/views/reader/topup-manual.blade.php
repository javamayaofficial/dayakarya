@extends('layouts.app')
@section('content')
<section class="section"><div class="container" style="max-width:440px">
<div class="card">
<h1 style="font-size:1.5rem;margin-bottom:12px">Transfer Manual</h1>
<div class="alert alert-success">Selesaikan pembayaran ke rekening di bawah, lalu unggah bukti transfer. Admin akan mengonfirmasi.</div>
<p><b>Bank:</b> {{ env('MANUAL_BANK_NAME') }}<br>
<b>No. Rekening:</b> {{ env('MANUAL_BANK_ACCOUNT') }}<br>
<b>Atas Nama:</b> {{ env('MANUAL_BANK_HOLDER') }}</p>
<a href="https://wa.me/62800000000?text=Halo%20admin%2C%20saya%20sudah%20transfer%20top%20up%20Credit%20Dayakarya" class="btn btn-wa btn-block" style="margin-top:16px">Konfirmasi via WhatsApp</a>
</div>
</div></section>
@endsection
