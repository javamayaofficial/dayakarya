@extends('layouts.app')
@section('title', 'Masuk — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Masuk ke ekosistem kreator</span>
                <h1>Masuk dan lanjutkan karya yang ingin Anda tumbuhkan dengan lebih serius.</h1>
                <p>Dayakarya dirancang untuk kreator dan penikmat karya yang ingin pengalaman lebih rapi, terasa premium, dan siap berkembang menjadi ekosistem bernilai.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Akses wallet dan royalti</strong>
                        <span>Pantau kredit, top up, dan histori monetisasi dalam satu alur yang rapi.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Lanjutkan pengalaman menikmati karya</strong>
                        <span>Buka konten premium, simpan progres, dan jelajahi katalog dengan pengalaman yang lebih nyaman.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Siap untuk kreator yang ingin naik kelas</strong>
                        <span>Bangun katalog, distribusi, dan pendapatan tanpa kehilangan kesan profesional.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Login Dayakarya</span>
                    <h2>Selamat datang kembali</h2>
                    <p>Masuk untuk melanjutkan berkarya, menikmati konten, dan mengelola akun Anda.</p>
                </div>

                <div id="msg"></div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="email" placeholder="nama@email.com" autocomplete="email">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" id="password" placeholder="Kata sandi" autocomplete="current-password">
                </div>
                <button class="btn btn-primary btn-block" onclick="doLogin()">Masuk ke Akun</button>
                <div class="auth-meta">
                    <span>Belum punya akun?</span>
                    <a href="/daftar">Daftar gratis</a>
                </div>
                <div class="auth-note">
                    Dengan masuk, Anda melanjutkan pengalaman Dayakarya yang lebih rapi, aman, dan siap bertumbuh.
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
