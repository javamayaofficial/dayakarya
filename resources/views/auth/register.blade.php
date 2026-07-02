@extends('layouts.app')
@section('title', 'Daftar — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Mulai Bareng Dayakarya</span>
                <h1>Bikin akun, lalu mulai taruh skill, hobi, dan karya kamu di tempat yang lebih enak dilihat.</h1>
                <p>Cocok buat kreator, pembaca, affiliate, sponsor, sampai partner yang mau semuanya terasa lebih rapi.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Buat kreator yang mau karyanya lebih bernilai</strong>
                        <span>Upload karya, buka akses berbayar, dan bangun pembaca pelan-pelan.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Buat penikmat karya yang mau pengalaman lebih nyaman</strong>
                        <span>Cari bacaan, audio, dan konten premium tanpa tampilan yang bikin capek.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Buat kolaborator yang mau kerja sama lebih gampang</strong>
                        <span>Affiliate, sponsor, dan program CSR bisa ketemu di tempat yang sama.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Buat Akun Dayakarya</span>
                    <h2>Mulai gratis hari ini</h2>
                    <p>Buat akun untuk mulai upload karya, jelajah katalog, atau buka peluang kolaborasi.</p>
                </div>

                <div id="msg"></div>
                @if (session('google_auth_error'))
                    <div class="alert alert-error">{{ session('google_auth_error') }}</div>
                @endif
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
                    <div class="hint">Untuk notifikasi royalti, top up, dan penarikan.</div>
                </div>
                <div class="field"><label>Password</label><input type="password" id="password" placeholder="Minimal 8 karakter"></div>
                <div class="field"><label>Ulangi password</label><input type="password" id="password_confirmation" placeholder="Ketik ulang"></div>
                <button class="btn btn-gold btn-block" onclick="doRegister()">Buat Akun</button>
                <a href="{{ route('auth.google.redirect') }}" class="btn btn-google btn-block auth-google-btn">
                    <span class="btn-google-mark">G</span>
                    <span>Daftar dengan Google</span>
                </a>
                <div class="auth-meta">
                    <span>Sudah punya akun?</span>
                    <a href="/masuk">Masuk sekarang</a>
                </div>
                <div class="auth-note">
                    Gratis daftar. Kalau serius berkarya, tempatnya sudah siap.
                </div>
            </div>
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
