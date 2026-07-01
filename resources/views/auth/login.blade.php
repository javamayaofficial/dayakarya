@extends('layouts.app')
@section('title', 'Masuk — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Akses Ruang Dayakarya</span>
                <h1>Masuk ke ruang yang membuat karya, audiens, dan monetisasi terasa lebih bernilai.</h1>
                <p>Dayakarya dirancang untuk kreator dan penikmat karya yang menginginkan pengalaman lebih elegan, lebih terstruktur, dan lebih layak dipercaya sejak interaksi pertama.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Kontrol wallet dan royalti</strong>
                        <span>Pantau credit, top up, dan histori pendapatan dalam satu alur yang terasa rapi dan profesional.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Nikmati karya dengan kelas yang lebih baik</strong>
                        <span>Buka konten premium, lanjutkan progres, dan jelajahi katalog dengan pengalaman yang lebih nyaman dan bernilai.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Layak untuk kreator yang ingin naik kelas</strong>
                        <span>Bangun katalog, distribusi, dan pendapatan tanpa kehilangan citra yang meyakinkan.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Login Dayakarya</span>
                    <h2>Selamat datang kembali</h2>
                    <p>Masuk untuk melanjutkan pengalaman berkarya, menikmati katalog premium, dan mengelola akun dengan lebih tenang.</p>
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
                    Dengan masuk, Anda kembali ke ekosistem yang dibangun untuk membuat karya terasa lebih layak dinikmati dan dihargai.
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
