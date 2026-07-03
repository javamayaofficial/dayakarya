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
                <h1>Atur credit dan transaksi tanpa ribet.</h1>
                <p>Saldo, top up, dan riwayatnya kelihatan jelas.</p>
            </div>
            <div class="wallet-hero-note">
                <span class="mini-label">Buat Pembayaran</span>
                <h2>Semuanya dibuat biar kamu lebih gampang top up dan pakai credit.</h2>
                <p>Dari isi saldo sampai cek riwayat, semuanya ada di satu tempat.</p>
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
                <p>Kalau ada pemasukan, angkanya kelihatan lebih jelas.</p>
            </div>
            <div class="balance-card balance-card-soft">
                <span class="label">Konversi Credit</span>
                <strong class="value">Rp{{ number_format(config('dayakarya.economy.credit_rate_rupiah'),0,',','.') }}</strong>
                <p>1 credit punya nilai rupiah yang jelas.</p>
            </div>
        </div>

        <div class="wallet-panel-grid">
            <div class="wallet-panel card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Top Up</span>
                        <h2>Isi credit sesuai kebutuhan</h2>
                    </div>
                </div>
                <p class="wallet-copy">Pilih nominal yang pas buat buka karya premium atau lanjut baca tanpa putus.</p>
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
                @if ($paymentProvider === 'duitku')
                    <div class="wallet-payment-method-box">
                        <span class="wallet-payment-method-label">Metode Pembayaran</span>
                        <button type="button" class="wallet-qris-spotlight" id="duitku-qris-spotlight" hidden>
                            <span class="wallet-qris-badge">QRIS Direkomendasikan</span>
                            <strong id="duitku-qris-name">QRIS akan tampil di sini</strong>
                            <span id="duitku-qris-meta">Kalau QRIS aktif di Duitku, metode ini akan ditonjolkan di sini supaya lebih cepat dipilih.</span>
                        </button>
                        <select id="duitku-method-select" class="wallet-method-select">
                            <option>Memuat metode pembayaran...</option>
                        </select>
                        <div class="wallet-method-current" id="duitku-method-current">
                            <strong id="duitku-method-current-name">Metode belum dipilih</strong>
                            <span id="duitku-method-current-meta">Pilih satu channel yang paling nyaman dipakai, lalu lanjut ke checkout Duitku.</span>
                        </div>
                        <div class="hint" id="duitku-method-hint">Pilih channel pembayaran yang paling nyaman buat top up ini. Satu pilihan saja sudah cukup.</div>
                    </div>
                @endif
                <button class="btn btn-gold btn-block" id="topup-button" onclick="doTopup()">{{ $paymentLabel }}</button>
                <div id="topup-msg" style="margin-top:12px"></div>
            </div>

            <div class="wallet-panel wallet-panel-info card">
                <div class="wallet-panel-head">
                    <div>
                        <span class="section-kicker">Biar Lebih Tenang</span>
                        <h2>Semua angkanya gampang dipahami</h2>
                    </div>
                </div>
                <div class="wallet-info-list">
                    <div class="wallet-info-item">
                        <strong>Nilai credit jelas</strong>
                        <span>Konversi dan total langsung kelihatan.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Riwayat rapi</strong>
                        <span>Setiap transaksi tercatat, jadi lebih gampang dicek.</span>
                    </div>
                    <div class="wallet-info-item">
                        <strong>Satu wallet, banyak kebutuhan</strong>
                        <span>Bisa dipakai pembaca, pendengar, dan kreator dalam alur yang sama.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-head section-head-premium" style="margin-top:24px">
            <div>
                <span class="section-kicker">Riwayat Transaksi</span>
                <h2>Semua pergerakan saldo ada catatannya</h2>
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
  const methodSelect = document.querySelector('#duitku-method-select');
  const methodHint = document.querySelector('#duitku-method-hint');
  const methodCurrentName = document.querySelector('#duitku-method-current-name');
  const methodCurrentMeta = document.querySelector('#duitku-method-current-meta');
  const qrisSpotlight = document.querySelector('#duitku-qris-spotlight');
  const qrisSpotlightName = document.querySelector('#duitku-qris-name');
  const qrisSpotlightMeta = document.querySelector('#duitku-qris-meta');
  let selectedPaymentMethod = null;
  let duitkuMethodsLoaded = PAYMENT_PROVIDER !== 'duitku';
  let availableDuitkuMethods = [];

  function isQrisMethod(method = {}) {
    const code = String(method.code || '').toUpperCase();
    const name = String(method.name || '').toUpperCase();
    return name.includes('QRIS')
      || name.includes('SHOPEEPAY QRIS')
      || ['DQ', 'GQ', 'LQ', 'NQ', 'SQ'].includes(code);
  }

  function formatMethodLabel(method = {}) {
    const name = String(method.name || method.code || 'Metode pembayaran');
    return isQrisMethod(method) ? `QRIS - ${name}` : name;
  }

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

  function renderMethodOptions(methods = []) {
    if (!methodSelect) return;

    methods = [...methods].sort((left, right) => {
      const leftRank = isQrisMethod(left) ? 0 : 1;
      const rightRank = isQrisMethod(right) ? 0 : 1;
      if (leftRank !== rightRank) {
        return leftRank - rightRank;
      }

      return String(left.name || '').localeCompare(String(right.name || ''), 'id');
    });

    availableDuitkuMethods = methods;

    if (!methods.length) {
      selectedPaymentMethod = null;
      methodSelect.innerHTML = '<option value="">Metode belum tersedia</option>';
      methodSelect.disabled = true;
      if (qrisSpotlight) {
        qrisSpotlight.hidden = true;
      }
      if (methodCurrentName) {
        methodCurrentName.textContent = 'Metode belum tersedia';
      }
      if (methodCurrentMeta) {
        methodCurrentMeta.textContent = 'Belum ada channel Duitku yang aktif untuk nominal ini.';
      }
      if (methodHint) {
        methodHint.textContent = 'Belum ada channel Duitku yang aktif untuk nominal ini. Cek pengaturan project Duitku Anda.';
      }
      topupButton.disabled = true;
      return;
    }

    if (!methods.some((item) => item.code === selectedPaymentMethod)) {
      selectedPaymentMethod = methods[0].code;
    }

    const qrisMethod = methods.find((item) => isQrisMethod(item));
    if (qrisSpotlight) {
      qrisSpotlight.hidden = !qrisMethod;
      qrisSpotlight.classList.toggle('is-active', Boolean(qrisMethod) && qrisMethod.code === selectedPaymentMethod);
    }
    if (qrisMethod && qrisSpotlightName && qrisSpotlightMeta) {
      qrisSpotlightName.textContent = formatMethodLabel(qrisMethod);
      qrisSpotlightMeta.textContent = Number(qrisMethod.fee || 0) > 0
        ? `Paling cepat dikenali dan tersedia dengan biaya Rp${Number(qrisMethod.fee || 0).toLocaleString('id-ID')}. Klik kartu ini kalau ingin langsung pakai QRIS.`
        : 'Paling cepat dikenali dan cocok kalau Anda ingin langsung scan QRIS. Klik kartu ini untuk langsung memilih QRIS.';
    }

    methodSelect.disabled = false;
    methodSelect.innerHTML = methods.map((item) => {
      const feeLabel = Number(item.fee || 0) > 0 ? ` - Biaya Rp${Number(item.fee || 0).toLocaleString('id-ID')}` : '';
      const selectedAttr = item.code === selectedPaymentMethod ? 'selected' : '';
      return `<option value="${item.code}" ${selectedAttr}>${formatMethodLabel(item)}${feeLabel}</option>`;
    }).join('');

    const activeMethod = methods.find((item) => item.code === selectedPaymentMethod);
    if (activeMethod) {
      if (methodCurrentName) {
        methodCurrentName.textContent = formatMethodLabel(activeMethod);
      }
      if (methodCurrentMeta) {
        const feeText = Number(activeMethod.fee || 0) > 0
          ? `Ada biaya Rp${Number(activeMethod.fee || 0).toLocaleString('id-ID')} untuk channel ini.`
          : 'Tidak ada biaya tambahan yang tercatat dari channel ini.';
        methodCurrentMeta.textContent = isQrisMethod(activeMethod)
          ? `Ini termasuk channel QRIS. ${feeText}`
          : feeText;
      }
      if (methodHint) {
        methodHint.textContent = isQrisMethod(activeMethod)
          ? 'QRIS aktif. Kalau ini yang Anda cari, langsung lanjut bayar tanpa perlu pilih metode lain.'
          : 'Kalau channel ini sudah cocok, langsung lanjut bayar. Tidak perlu pilih terlalu banyak opsi.';
      }
    }

    topupButton.disabled = false;
  }

  async function loadDuitkuPaymentMethods() {
    if (PAYMENT_PROVIDER !== 'duitku' || !DK.token() || !methodSelect) return;

    duitkuMethodsLoaded = false;
    topupButton.disabled = true;
    methodSelect.disabled = true;
    methodSelect.innerHTML = '<option value="">Memuat metode pembayaran...</option>';
    if (methodCurrentName) {
      methodCurrentName.textContent = 'Memuat metode pembayaran';
    }
    if (methodCurrentMeta) {
      methodCurrentMeta.textContent = 'Sedang mengambil daftar channel yang aktif dari proyek Duitku.';
    }
    if (methodHint) {
      methodHint.textContent = 'Sedang mengambil channel pembayaran aktif dari proyek Duitku.';
    }

    try {
      const methodsResponse = await DK.get('/wallet/payment-methods?credit_amount=' + selected);
      if (!Array.isArray(methodsResponse.methods)) {
        duitkuMethodsLoaded = false;
        renderMethodOptions([]);
        topupMessage.innerHTML = '<div class="alert alert-error">'+(methodsResponse.message || 'Metode pembayaran Duitku belum tersedia untuk nominal ini.')+'</div>';
        return;
      }

      const methods = methodsResponse.methods ?? [];
      duitkuMethodsLoaded = true;
      renderMethodOptions(methods);
    } catch (_) {
      duitkuMethodsLoaded = false;
      renderMethodOptions([]);
      topupMessage.innerHTML = '<div class="alert alert-error">Metode pembayaran Duitku belum berhasil dimuat. Coba lagi sebentar.</div>';
    }
  }

  chips.forEach(c => c.addEventListener('click', () => {
    if (!DK.token()) return;
    chips.forEach(x=>x.classList.remove('active'));
    c.classList.add('active'); selected = +c.dataset.credit; renderTotal();
    if (PAYMENT_PROVIDER === 'duitku') {
      loadDuitkuPaymentMethods();
    }
  }));
  methodSelect?.addEventListener('change', () => {
    selectedPaymentMethod = methodSelect.value || null;
    renderMethodOptions(availableDuitkuMethods);
  });
  qrisSpotlight?.addEventListener('click', () => {
    const qrisMethod = availableDuitkuMethods.find((item) => isQrisMethod(item));
    if (!qrisMethod) return;
    selectedPaymentMethod = qrisMethod.code;
    renderMethodOptions(availableDuitkuMethods);
  });
  renderTotal();

  function renderGuestWalletState(){
    document.querySelector('#credit-balance').textContent = '0';
    document.querySelector('#rupiah-balance').textContent = 'Rp0';
    topupButton.disabled = true;
    topupButton.textContent = 'Masuk Untuk Membuka Wallet';
    topupMessage.innerHTML = `
      <div class="alert alert-success">
        Wallet ini pakai login akun pengguna, bukan session admin.
        <a href="/masuk" style="font-weight:700;text-decoration:underline">Masuk sekarang</a>
        untuk melihat saldo, histori, dan top up.
      </div>`;
    document.querySelector('#trx-list').innerHTML = `
      <div class="state">
        <div class="emoji">🔐</div>
        <h3>Wallet baru terbuka setelah kamu masuk</h3>
        <p>Masuk untuk melihat saldo, histori, dan top up.</p>
        <a href="/masuk" class="btn btn-primary">Masuk ke Akun</a>
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
      if (PAYMENT_PROVIDER === 'duitku') {
        await loadDuitkuPaymentMethods();
      }

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
    if (PAYMENT_PROVIDER === 'duitku' && (!duitkuMethodsLoaded || !selectedPaymentMethod)) {
      msg.innerHTML = '<div class="alert alert-error">Pilih dulu metode pembayaran Duitku yang ingin dipakai.</div>';
      await loadDuitkuPaymentMethods();
      return;
    }

    const payload = { credit_amount: selected };
    if (PAYMENT_PROVIDER === 'duitku') {
      payload.payment_method = selectedPaymentMethod;
    }

    const { ok, data } = await DK.post('/topup', payload);
    if (ok && data.payment_url) { location.href = data.payment_url; }
    else if (ok) { msg.innerHTML = '<div class="alert alert-success">'+(data.message||'Ikuti instruksi pembayaran.')+'</div>'; }
    else { msg.innerHTML = '<div class="alert alert-error">'+(data.message||'Gagal membuat transaksi.')+'</div>'; }
  }

  loadWallet();
</script>
@endpush
