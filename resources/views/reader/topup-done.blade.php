@extends('layouts.app')
@section('title', 'Pembayaran Diproses — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel state state-panel">
            <div class="emoji">🎉</div>
            <h3>Pembayaran sedang diproses</h3>
            <p id="topup-done-copy">Setelah terkonfirmasi, credit akan masuk otomatis ke wallet Anda.</p>
            <a href="{{ route('wallet') }}" class="btn btn-primary" id="topup-done-primary">Kembali ke Wallet</a>
            <a href="{{ route('wallet') }}" class="btn btn-ghost" id="topup-done-secondary" hidden>Lihat Wallet</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const topupDonePrimary = document.querySelector('#topup-done-primary');
  const topupDoneSecondary = document.querySelector('#topup-done-secondary');
  const topupDoneCopy = document.querySelector('#topup-done-copy');
  const returnTarget = window.DK ? DK.getTopupReturnUrl('/wallet') : '/wallet';

  if (returnTarget && returnTarget !== '/wallet') {
    topupDonePrimary.href = returnTarget;
    topupDonePrimary.textContent = 'Kembali ke Cerita';
    topupDoneSecondary.hidden = false;
    topupDoneCopy.textContent = 'Setelah credit masuk, kamu bisa lanjut lagi ke bagian karya yang tadi dipilih tanpa mulai dari awal.';
  }

  [topupDonePrimary, topupDoneSecondary].forEach((link) => {
    link?.addEventListener('click', () => {
      if (window.DK) {
        DK.clearTopupReturnUrl();
      }
    });
  });
</script>
@endpush
