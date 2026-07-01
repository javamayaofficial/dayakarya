@extends('layouts.app')
@section('title', 'Wallet & Credit — Dayakarya')
@section('body_class', 'page-wallet')

@section('content')
<section class="section">
    <div class="container wallet-container">
        <div class="wallet-hero">
            <div class="wallet-hero-copy">
                <span class="section-kicker">Wallet Dayakarya</span>
                <h1>Kelola credit, saldo, dan transaksi di ruang yang terasa lebih aman, lebih jelas, dan lebih layak dipercaya.</h1>
                <p>Wallet Dayakarya dirancang agar pengguna tidak sekadar bertransaksi, tetapi merasa tenang karena setiap nilai, alur, dan histori ditampilkan dengan lebih rapi.</p>
            </div>
            <div class="wallet-hero-note">
                <span class="mini-label">Financial Layer</span>
                <h2>Transaksi yang baik tidak hanya lancar, tetapi juga menumbuhkan rasa percaya.</h2>
                <p>Top up, histori, dan saldo disusun dengan tampilan yang lebih tenang dan lebih profesional agar setiap interaksi finansial terasa matang.</p>
            </div>
        </div>

        <div class="wallet-balance-grid">
            <div class="balance-card balance-card-credit">
                <span class="label">Saldo Credit</span>
                <strong class="value" id="credit-balance">—</strong>
                <p>Gunakan credit untuk membuka karya premium dan membeli pengalaman yang terasa lebih utuh.</p>
            </div>
            <div class="balance-card balance-card-rupiah">
                <span class="label">Saldo Rupiah</span>
                <strong class="value" id="rupiah-balance">—</strong>
                <p>Pendapatan kreator dan aliran monetisasi dirangkum agar mudah dipantau dan terasa lebih transparan.</p>
            </div>
            <div class="balance-card balance-card-soft">
                <span class="label">Konversi Credit</span>
                <strong class="value">Rp{{ number_format(config('dayakarya.economy.credit_rate_rupiah'),0,',','.') }}</strong>
                <p>Setiap 1 credit memiliki nilai rupiah yang jelas, sehingga keputusan transaksi terasa lebih aman.</p>
            </div>
        </div>

        <div class="wallet-panel-grid">
            <div class="wallet-panel card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Top Up</span>
                        <h2>Isi credit tanpa kehilangan rasa percaya</h2>
                    </div>
                </div>
                <p class="wallet-copy">Pilih nominal yang paling sesuai untuk membuka karya premium, mendukung kreator, dan menikmati katalog dengan akses yang lebih dalam.</p>
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
                <button class="btn btn-gold btn-block" id="topup-button" onclick="doTopup()">Bayar dengan Duitku</button>
                <div id="topup-msg" style="margin-top:12px"></div>
            </div>

            <div class="wallet-panel wallet-panel-info card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Kepercayaan Sistem</span>
                        <h2>Semua dibuat agar mudah dimengerti sebelum dibayar</h2>
                    </div>
                </div>
                <div class="wallet-info-list">
                    <div class="wallet-info-item">
                        <strong>Nilai credit selalu terang</strong>
                        <span>Tidak ada angka yang ambigu. Konversi dan total selalu tampil di depan sebelum Anda membayar.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Histori mudah diaudit</strong>
                        <span>Setiap aktivitas dompet ditampilkan agar pengguna merasa lebih aman, lebih sadar, dan lebih terkontrol.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Dibangun untuk monetisasi yang rapi</strong>
                        <span>Wallet ini dirancang untuk mendukung pembaca, pendengar, dan kreator dalam satu ekosistem yang terasa profesional.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-head section-head-premium" style="margin-top:24px">
            <div>
                <span class="section-kicker">Riwayat Transaksi</span>
                <h2>Setiap pergerakan saldo tercatat agar rasa percaya tetap terjaga</h2>
            </div>
        </div>
        <div id="trx-list"><div class="state"><div class="emoji">🧾</div><p>Memuat riwayat…</p></div></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const RATE = {{ (int) config('dayakarya.economy.credit_rate_rupiah') }};
  let selected = 100;
  const topupButton = document.querySelector('#topup-button');
  const topupMessage = document.querySelector('#topup-msg');
  const chips = document.querySelectorAll('#topup-options .chip');

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
        Wallet menggunakan login akun pengguna, bukan session admin panel.
        <a href="/masuk" style="font-weight:700;text-decoration:underline">Masuk sekarang</a>
        untuk melihat saldo, histori, dan melanjutkan top up.
      </div>`;
    document.querySelector('#trx-list').innerHTML = `
      <div class="state">
        <div class="emoji">🔐</div>
        <h3>Wallet siap setelah Anda masuk</h3>
        <p>Silakan login dengan akun pengguna Dayakarya agar saldo, histori transaksi, dan top up dapat ditampilkan dengan benar.</p>
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
      topupButton.textContent = 'Bayar dengan Duitku';

      const trx = await DK.get('/wallet/transactions');
      const items = trx.data ?? [];
      document.querySelector('#trx-list').innerHTML = items.length ? items.map(t => `
        <div class="chapter-row wallet-trx-row"><div><div style="font-weight:700">${t.description||t.type}</div>
        <div class="work-meta">${new Date(t.created_at).toLocaleDateString('id-ID')}</div></div>
        <div class="wallet-trx-amount" style="color:${t.amount<0?'var(--danger)':'var(--teal-deep)'}">${t.amount>0?'+':''}${(+t.amount).toLocaleString('id-ID')}</div></div>
      `).join('') : `<div class="state"><div class="emoji">🌱</div><h3>Belum ada transaksi</h3><p>Top up untuk mulai membuka karya premium.</p></div>`;
    } catch (_) {
      topupMessage.innerHTML = '<div class="alert alert-error">Wallet belum berhasil dimuat. Silakan muat ulang halaman atau masuk kembali ke akun Anda.</div>';
      document.querySelector('#trx-list').innerHTML = `
        <div class="state">
          <div class="emoji">⚠️</div>
          <h3>Wallet belum berhasil dimuat</h3>
          <p>Periksa koneksi atau login akun Anda, lalu coba lagi dalam beberapa saat.</p>
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
