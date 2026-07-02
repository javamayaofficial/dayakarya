@extends('layouts.app')
@section('title', 'Wallet & Credit — Dayakarya')
@section('body_class', 'page-wallet')

@section('content')
@php
    $paymentProvider = \App\Support\IntegrationSettings::get('providers.payment', config('dayakarya.providers.payment'));
    $paymentLabel = match ($paymentProvider) {
        'manual' => 'Lanjutkan Transfer Manual',
        'qris_manual' => 'Lanjutkan Pembayaran QRIS',
        default => 'Bayar dengan Duitku',
    };
@endphp
<section class="section">
    <div class="container wallet-container">
        <div class="wallet-hero">
            <div class="wallet-hero-copy">
                <span class="section-kicker">Wallet Dayakarya</span>
                <h1>Kelola credit dan transaksi dengan lebih tenang.</h1>
                <p>Saldo, top up, dan histori ditampilkan dengan jelas.</p>
            </div>
            <div class="wallet-hero-note">
                <span class="mini-label">Financial Layer</span>
                <h2>Transaksi yang rapi membangun rasa percaya.</h2>
                <p>Top up, histori, dan saldo tampil dalam alur yang bersih.</p>
            </div>
        </div>

        <div class="wallet-balance-grid">
            <div class="balance-card balance-card-credit">
                <span class="label">Saldo Credit</span>
                <strong class="value" id="credit-balance">—</strong>
                <p>Gunakan credit untuk membuka karya premium.</p>
            </div>
            <div class="balance-card balance-card-rupiah">
                <span class="label">Saldo Rupiah</span>
                <strong class="value" id="rupiah-balance">—</strong>
                <p>Pendapatan dan aliran nilai terlihat lebih transparan.</p>
            </div>
            <div class="balance-card balance-card-soft">
                <span class="label">Konversi Credit</span>
                <strong class="value">Rp{{ number_format(config('dayakarya.economy.credit_rate_rupiah'),0,',','.') }}</strong>
                <p>1 credit memiliki nilai rupiah yang jelas.</p>
            </div>
        </div>

        <div class="wallet-panel-grid">
            <div class="wallet-panel card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Top Up</span>
                        <h2>Isi credit dengan alur yang jelas</h2>
                    </div>
                </div>
                <p class="wallet-copy">Pilih nominal yang sesuai untuk membuka akses premium.</p>
                <div class="chips chips-elevated" id="topup-options">
                    <span class="chip" data-credit="50">50</span>
                    <span class="chip active" data-credit="100">100</span>
                    <span class="chip" data-credit="250">250</span>
                    <span class="chip" data-credit="500">500</span>
                    <span class="chip" data-credit="1000">1.000</span>
                </div>
                <div class="wallet-total-box">
                    <span>Total Pembayaran</span>
                    <strong id="total-rp">Rp10.000</strong>
                    <p>1 Credit = Rp{{ number_format(config('dayakarya.economy.credit_rate_rupiah'),0,',','.') }}</p>
                </div>
                <button class="btn btn-gold btn-block" id="topup-button" onclick="doTopup()">{{ $paymentLabel }}</button>
                <div id="topup-msg" style="margin-top:12px"></div>
            </div>

            <div class="wallet-panel wallet-panel-info card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Kepercayaan Sistem</span>
                        <h2>Semua dibuat agar mudah dipahami</h2>
                    </div>
                </div>
                <div class="wallet-info-list">
                    <div class="wallet-info-item">
                        <strong>Nilai credit selalu jelas</strong>
                        <span>Konversi dan total selalu tampil di depan.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Histori mudah diaudit</strong>
                        <span>Setiap aktivitas dompet tercatat rapi.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Dibangun untuk monetisasi yang rapi</strong>
                        <span>Satu wallet untuk pembaca, pendengar, dan kreator.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-head section-head-premium" style="margin-top:24px">
            <div>
                <span class="section-kicker">Riwayat Transaksi</span>
                <h2>Setiap pergerakan saldo tercatat rapi</h2>
            </div>
        </div>
        <div id="trx-list"><div class="state"><div class="emoji">🧾</div><p>Memuat riwayat…</p></div></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const RATE = {{ (int) config('dayakarya.economy.credit_rate_rupiah') }};
  const PAYMENT_PROVIDER = @json($paymentProvider);
  const PAYMENT_LABEL = @json($paymentLabel);
  let selected = 100;
  const topupButton = document.querySelector('#topup-button');
  const topupMessage = document.querySelector('#topup-msg');
  const chips = document.querySelectorAll('#topup-options .chip');

  function defaultTopupMessage() {
    if (PAYMENT_PROVIDER === 'manual') {
      return 'Transfer manual akan menampilkan rekening tujuan dan detail verifikasi.';
    }

    if (PAYMENT_PROVIDER === 'qris_manual') {
      return 'QRIS manual akan menampilkan instruksi pembayaran dan verifikasi.';
    }

    return '';
  }

  function renderTotal(){ document.querySelector('#total-rp').textContent = 'Rp' + (selected*RATE).toLocaleString('id-ID'); }
  chips.forEach(c => c.addEventListener('click', () => {
    if (!DK.token()) return;
    chips.forEach(x=>x.classList.remove('active'));
    c.classList.add('active'); selected = +c.dataset.credit; renderTotal();
  }));
  renderTotal();

  function renderGuestWalletState(){
    document.querySelector('#credit-balance').textContent = '0';
    document.querySelector('#rupiah-balance').textContent = 'Rp0';
    topupButton.disabled = true;
    topupButton.textContent = 'Masuk Untuk Membuka Wallet';
    topupMessage.innerHTML = `
      <div class="alert alert-success">
        Wallet memakai login akun pengguna, bukan session admin.
        <a href="/masuk" style="font-weight:700;text-decoration:underline">Masuk sekarang</a>
        untuk melihat saldo, histori, dan top up.
      </div>`;
    document.querySelector('#trx-list').innerHTML = `
      <div class="state">
        <div class="emoji">🔐</div>
        <h3>Wallet siap setelah Anda masuk</h3>
        <p>Masuk untuk melihat saldo, histori, dan top up.</p>
        <a href="/masuk" class="btn btn-primary">Masuk Ke Akun Pengguna</a>
      </div>`;
  }

  async function loadWallet(){
    if(!DK.token()){
      renderGuestWalletState();
      return;
    }

    try {
      const w = await DK.get('/wallet');
      document.querySelector('#credit-balance').textContent = (w.credit_balance||0).toLocaleString('id-ID');
      document.querySelector('#rupiah-balance').textContent = 'Rp'+(w.rupiah_balance||0).toLocaleString('id-ID');
      topupButton.disabled = false;
      topupButton.textContent = PAYMENT_LABEL;
      topupMessage.innerHTML = defaultTopupMessage() ? `<div class="alert alert-success">${defaultTopupMessage()}</div>` : '';

      const trx = await DK.get('/wallet/transactions');
      const items = trx.data ?? [];
      document.querySelector('#trx-list').innerHTML = items.length ? items.map(t => `
        <div class="chapter-row wallet-trx-row"><div><div style="font-weight:700">${t.description||t.type}</div>
        <div class="work-meta">${new Date(t.created_at).toLocaleDateString('id-ID')}</div></div>
        <div class="wallet-trx-amount" style="color:${t.amount<0?'var(--danger)':'var(--teal-deep)'}">${t.amount>0?'+':''}${(+t.amount).toLocaleString('id-ID')}</div></div>
      `).join('') : `<div class="state"><div class="emoji">🌱</div><h3>Belum ada transaksi</h3><p>Top up untuk mulai membuka akses premium.</p></div>`;
    } catch (_) {
      topupMessage.innerHTML = '<div class="alert alert-error">Wallet belum berhasil dimuat. Muat ulang atau masuk kembali.</div>';
      document.querySelector('#trx-list').innerHTML = `
        <div class="state">
          <div class="emoji">⚠️</div>
          <h3>Wallet belum berhasil dimuat</h3>
          <p>Periksa koneksi atau login Anda, lalu coba lagi.</p>
        </div>`;
    }
  }

  async function doTopup(){
    if (!DK.token()) {
      renderGuestWalletState();
      return;
    }

    const msg = topupMessage;
    const { ok, data } = await DK.post('/topup', { credit_amount: selected });
    if (ok && data.payment_url) { location.href = data.payment_url; }
    else if (ok) { msg.innerHTML = '<div class="alert alert-success">'+(data.message||'Ikuti instruksi pembayaran.')+'</div>'; }
    else { msg.innerHTML = '<div class="alert alert-error">'+(data.message||'Gagal membuat transaksi.')+'</div>'; }
  }

  loadWallet();
</script>
@endpush
