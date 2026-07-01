@extends('layouts.app')
@section('title', 'Daftar — Dayakarya')

@section('content')
<section class="section">
    <div class="container" style="max-width:460px">
        <div style="text-align:center;padding:14px 0 22px">
            <h1 style="font-size:1.8rem">Gabung Dayakarya</h1>
            <p style="color:var(--muted)">Gratis. Mulai berkarya atau menikmati karya hari ini.</p>
        </div>
        <div class="card">
            <div id="msg"></div>
            <div class="field">
                <label>Saya ingin menjadi</label>
                <select id="role">
                    <option value="creator">Kreator (menulis / mendongeng / podcast)</option>
                    <option value="reader">Pembaca</option>
                    <option value="listener">Pendengar</option>
                    <option value="affiliate">Affiliate (promosi & komisi)</option>
                    <option value="sponsor">Sponsor</option>
                    <option value="csr">Perusahaan / CSR</option>
                </select>
            </div>
            <div class="field"><label>Nama lengkap</label><input id="name" placeholder="Nama kamu"></div>
            <div class="field"><label>Email</label><input type="email" id="email" placeholder="nama@email.com"></div>
            <div class="field">
                <label>Nomor WhatsApp</label>
                <input id="phone" placeholder="08xxxxxxxxxx">
                <div class="hint">Untuk notifikasi royalti, top up, dan penarikan dana.</div>
            </div>
            <div class="field"><label>Password</label><input type="password" id="password" placeholder="Minimal 8 karakter"></div>
            <div class="field"><label>Ulangi password</label><input type="password" id="password_confirmation" placeholder="Ketik ulang"></div>
            <button class="btn btn-gold btn-block" onclick="doRegister()">Buat Akun</button>
            <p style="text-align:center;margin-top:16px;color:var(--muted);font-size:.9rem">
                Sudah punya akun? <a href="/masuk" style="color:var(--gold-deep);font-weight:600">Masuk</a>
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  async function doRegister() {
    const msg = document.querySelector('#msg');
    const body = ['role','name','email','phone','password','password_confirmation']
      .reduce((o,k)=>(o[k]=document.querySelector('#'+k).value,o),{});
    const { ok, data } = await DK.post('/auth/register', body);
    if (ok) {
      DK.setToken(data.token);
      msg.innerHTML = '<div class="alert alert-success">Akun dibuat! Mengalihkan…</div>';
      setTimeout(() => location.href = '/', 800);
    } else {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal mendaftar.');
      msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
    }
  }
</script>
@endpush
