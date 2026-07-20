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
            <div class="work-soft-note" id="topup-done-status-note" hidden></div>
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
  const topupDoneTitle = document.querySelector('.support-panel h3');
  const topupDoneStatusNote = document.querySelector('#topup-done-status-note');
  const returnTarget = window.DK ? DK.getTopupReturnUrl('/wallet') : '/wallet';
  const pendingTopup = window.DK ? DK.getPendingTopup() : null;
  let statusPollHandle = null;

  if (returnTarget && returnTarget !== '/wallet') {
    topupDonePrimary.href = returnTarget;
    topupDonePrimary.textContent = 'Kembali ke Cerita';
    topupDoneSecondary.hidden = false;
    topupDoneCopy.textContent = 'Setelah credit masuk, kamu bisa lanjut lagi ke bagian karya yang tadi dipilih tanpa mulai dari awal.';
  }

  function setStatusNote(message = '', visible = false) {
    if (!topupDoneStatusNote) return;
    topupDoneStatusNote.hidden = !visible;
    topupDoneStatusNote.textContent = message;
  }

  function stopStatusPolling() {
    if (statusPollHandle) {
      clearTimeout(statusPollHandle);
      statusPollHandle = null;
    }
  }

  async function refreshPendingTopupStatus(attempt = 1) {
    if (!window.DK || !pendingTopup?.payment_id || !DK.token()) return;

    try {
      const status = await DK.get(`/payments/${pendingTopup.payment_id}`);
      const paymentStatus = String(status?.status || '').toLowerCase();
      const paidAt = status?.paid_at
        ? new Date(status.paid_at).toLocaleString('id-ID')
        : null;

      if (paymentStatus === 'paid') {
        topupDoneTitle.textContent = 'Top up berhasil masuk';
        topupDoneCopy.textContent = `Credit ${Number(status.credit_amount || pendingTopup.credit_amount || 0).toLocaleString('id-ID')} sudah masuk ke akun Anda.`;
        setStatusNote(paidAt ? `Pembayaran terkonfirmasi pada ${paidAt}.` : 'Pembayaran sudah terkonfirmasi dan saldo akan langsung bisa dipakai.', true);
        topupDonePrimary.textContent = returnTarget !== '/wallet' ? 'Lanjut Buka Bagian' : 'Buka Wallet';
        topupDoneSecondary.hidden = false;
        topupDoneSecondary.textContent = returnTarget !== '/wallet' ? 'Lihat Wallet' : 'Kembali ke Explore';
        if (returnTarget === '/wallet') {
          topupDoneSecondary.href = '/explore';
        }
        DK.clearPendingTopup();
        stopStatusPolling();
        return;
      }

      if (paymentStatus === 'failed') {
        topupDoneTitle.textContent = 'Pembayaran belum berhasil';
        topupDoneCopy.textContent = 'Transaksi ini belum berhasil dikonfirmasi. Coba cek lagi metode pembayaran atau ulangi top up dari wallet.';
        setStatusNote(pendingTopup.order_id ? `Order ID: ${pendingTopup.order_id}` : 'Status transaksi terakhir: gagal.', true);
        topupDonePrimary.href = '/wallet';
        topupDonePrimary.textContent = 'Kembali ke Wallet';
        topupDoneSecondary.hidden = true;
        DK.clearPendingTopup();
        stopStatusPolling();
        return;
      }

      setStatusNote(
        pendingTopup.order_id
          ? `Order ID ${pendingTopup.order_id} masih menunggu konfirmasi payment gateway.`
          : 'Transaksi masih menunggu konfirmasi payment gateway.',
        true
      );
    } catch (_) {
      setStatusNote('Status pembayaran belum berhasil dicek otomatis. Anda tetap bisa cek ulang dari wallet beberapa saat lagi.', true);
    }

    if (attempt < 10) {
      statusPollHandle = setTimeout(() => refreshPendingTopupStatus(attempt + 1), 3000);
    }
  }

  [topupDonePrimary, topupDoneSecondary].forEach((link) => {
    link?.addEventListener('click', () => {
      if (window.DK) {
        DK.clearTopupReturnUrl();
        DK.clearPendingTopup();
      }
      stopStatusPolling();
    });
  });

  if (pendingTopup?.order_id) {
    setStatusNote(`Order ID ${pendingTopup.order_id} sedang dipantau. Kalau payment gateway belum selesai memproses, status bisa butuh beberapa detik untuk diperbarui.`, true);
  }

  refreshPendingTopupStatus();
</script>
@endpush
