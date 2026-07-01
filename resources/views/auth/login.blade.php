@extends('layouts.app')
@section('title', 'Masuk — Dayakarya')

@section('content')
<section class="section">
    <div class="container" style="max-width:440px">
        <div style="text-align:center;padding:14px 0 22px">
            <h1 style="font-size:1.8rem">Selamat datang kembali</h1>
            <p style="color:var(--muted)">Masuk untuk melanjutkan berkarya & menikmati konten.</p>
        </div>
        <div class="card">
            <div id="msg"></div>
            <div class="field">
                <label>Email</label>
                <input type="email" id="email" placeholder="nama@email.com" autocomplete="email">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" id="password" placeholder="Kata sandi" autocomplete="current-password">
            </div>
            <button class="btn btn-primary btn-block" onclick="doLogin()">Masuk</button>
            <p style="text-align:center;margin-top:16px;color:var(--muted);font-size:.9rem">
                Belum punya akun? <a href="/daftar" style="color:var(--gold-deep);font-weight:600">Daftar gratis</a>
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  async function doLogin() {
    const msg = document.querySelector('#msg');
    const { ok, data } = await DK.post('/auth/login', {
      email: document.querySelector('#email').value,
      password: document.querySelector('#password').value,
    });
    if (ok) {
      DK.setToken(data.token);
      msg.innerHTML = '<div class="alert alert-success">Berhasil masuk. Mengalihkan…</div>';
      setTimeout(() => location.href = '/', 700);
    } else {
      const err = data.errors?.email?.[0] || data.message || 'Gagal masuk.';
      msg.innerHTML = `<div class="alert alert-error">${err}</div>`;
    }
  }
</script>
@endpush
