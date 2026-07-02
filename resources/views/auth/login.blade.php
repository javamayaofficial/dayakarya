@extends('layouts.app')
@section('title', 'Masuk — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Akses Ruang Dayakarya</span>
                <h1>Masuk ke ruang karya yang terasa bernilai.</h1>
                <p>Dayakarya menghadirkan akses yang rapi untuk karya, audiens, dan monetisasi.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Wallet dan royalti</strong>
                        <span>Pantau credit, top up, dan histori dalam satu alur.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Nikmati karya premium</strong>
                        <span>Buka konten premium dan jelajahi katalog dengan nyaman.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Untuk kreator yang ingin naik kelas</strong>
                        <span>Bangun katalog dan pendapatan dengan citra yang rapi.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Login Dayakarya</span>
                    <h2>Selamat datang kembali</h2>
                    <p>Masuk untuk lanjut membaca, berkarya, dan mengelola akun.</p>
                </div>

                <div id="msg"></div>
                @if (session('google_auth_error'))
                    <div class="alert alert-error">{{ session('google_auth_error') }}</div>
                @endif
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="email" placeholder="nama@email.com" autocomplete="email">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" id="password" placeholder="Kata sandi" autocomplete="current-password">
                </div>
                <button class="btn btn-primary btn-block" onclick="doLogin()">Masuk ke Akun</button>
                <a href="{{ route('auth.google.redirect') }}" class="btn btn-google btn-block auth-google-btn">
                    <span class="btn-google-mark">G</span>
                    <span>Masuk dengan Google</span>
                </a>
                <div class="auth-meta">
                    <span>Belum punya akun?</span>
                    <a href="/daftar">Buat akun gratis</a>
                </div>
                <div class="auth-note">
                    Masuk untuk kembali ke Dayakarya.
                </div>
            </div>
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
