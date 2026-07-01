@extends('layouts.app')
@section('title', 'Wallet & Credit — Dayakarya')

@section('content')
<section class="section">
    <div class="container" style="max-width:480px">
        <div class="section-head"><h2>Wallet</h2></div>

        <div class="stat-grid">
            <div class="stat gold"><div class="label">Saldo Credit</div><div class="value" id="credit-balance">—</div></div>
            <div class="stat teal"><div class="label">Saldo Rupiah</div><div class="value" id="rupiah-balance">—</div></div>
        </div>

        <div class="card" style="margin-top:16px">
            <h3 style="margin-bottom:12px">Top Up Credit</h3>
            <div class="chips" id="topup-options">
                <span class="chip" data-credit="50">50</span>
                <span class="chip active" data-credit="100">100</span>
                <span class="chip" data-credit="250">250</span>
                <span class="chip" data-credit="500">500</span>
                <span class="chip" data-credit="1000">1.000</span>
            </div>
            <div class="hint" style="margin:10px 0">1 Credit = Rp{{ number_format(config('dayakarya.economy.credit_rate_rupiah'),0,',','.') }}. Total: <b id="total-rp">Rp10.000</b></div>
            <button class="btn btn-gold btn-block" onclick="doTopup()">Bayar dengan Duitku</button>
            <div id="topup-msg" style="margin-top:12px"></div>
        </div>

        <div class="section-head" style="margin-top:24px"><h2>Riwayat</h2></div>
        <div id="trx-list"><div class="state"><div class="emoji">🧾</div><p>Memuat riwayat…</p></div></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const RATE = {{ (int) config('dayakarya.economy.credit_rate_rupiah') }};
  let selected = 100;

  function renderTotal(){ document.querySelector('#total-rp').textContent = 'Rp' + (selected*RATE).toLocaleString('id-ID'); }
  document.querySelectorAll('#topup-options .chip').forEach(c => c.addEventListener('click', () => {
    document.querySelectorAll('#topup-options .chip').forEach(x=>x.classList.remove('active'));
    c.classList.add('active'); selected = +c.dataset.credit; renderTotal();
  }));
  renderTotal();

  async function loadWallet(){
    if(!DK.token()){ location.href='/masuk'; return; }
    const w = await DK.get('/wallet');
    document.querySelector('#credit-balance').textContent = (w.credit_balance||0).toLocaleString('id-ID');
    document.querySelector('#rupiah-balance').textContent = 'Rp'+(w.rupiah_balance||0).toLocaleString('id-ID');
    const trx = await DK.get('/wallet/transactions');
    const items = trx.data ?? [];
    document.querySelector('#trx-list').innerHTML = items.length ? items.map(t => `
      <div class="chapter-row"><div><div style="font-weight:600">${t.description||t.type}</div>
      <div class="work-meta">${new Date(t.created_at).toLocaleDateString('id-ID')}</div></div>
      <div style="font-weight:700;color:${t.amount<0?'var(--danger)':'var(--teal-deep)'}">${t.amount>0?'+':''}${(+t.amount).toLocaleString('id-ID')}</div></div>
    `).join('') : `<div class="state"><div class="emoji">🌱</div><h3>Belum ada transaksi</h3><p>Top up untuk mulai membuka karya premium.</p></div>`;
  }

  async function doTopup(){
    const msg = document.querySelector('#topup-msg');
    const { ok, data } = await DK.post('/topup', { credit_amount: selected });
    if (ok && data.payment_url) { location.href = data.payment_url; }
    else if (ok) { msg.innerHTML = '<div class="alert alert-success">'+(data.message||'Ikuti instruksi pembayaran.')+'</div>'; }
    else { msg.innerHTML = '<div class="alert alert-error">'+(data.message||'Gagal membuat transaksi.')+'</div>'; }
  }

  loadWallet();
</script>
@endpush
