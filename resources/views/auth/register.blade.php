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
                <p>Satu akun buat masuk ke area member, nikmati karya orang lain, atau mulai bikin karya kamu sendiri saat sudah siap.</p>
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
                        <strong>Buat yang mau ikut cari cuan juga</strong>
                        <span>Kalau ada karya yang kamu suka, kamu bisa bantu share dan ikut besarkan jangkauannya.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Buat Akun Dayakarya</span>
                    <h2>Mulai gratis hari ini</h2>
                    <p>Buat akun untuk masuk ke dashboard member, baca karya, atau mulai bikin karya kapan pun kamu siap.</p>
                </div>

                <div id="msg"></div>
                @if (session('google_auth_error'))
                    <div class="alert alert-error">{{ session('google_auth_error') }}</div>
                @endif
                <div class="field"><label>Nama lengkap</label><input id="name" placeholder="Nama kamu"></div>
                <div class="field"><label>Email</label><input type="email" id="email" placeholder="nama@email.com"></div>
                <div class="field">
                    <label>Pilih mode awal akun</label>
                    <div class="auth-persona-grid" id="persona-options">
                        <label class="auth-persona-option">
                            <input type="radio" name="persona" value="reader" checked>
                            <span class="auth-persona-card">
                                <strong>Pengguna / Pembaca</strong>
                                <span>Masuk untuk jelajah karya, buka konten premium, dan pakai wallet saat dibutuhkan.</span>
                            </span>
                        </label>
                        <label class="auth-persona-option">
                            <input type="radio" name="persona" value="writer">
                            <span class="auth-persona-card">
                                <strong>Penulis / Kreator Teks</strong>
                                <span>Langsung masuk ke area produksi untuk bikin cerpen, novel, atau karya premium.</span>
                            </span>
                        </label>
                        <label class="auth-persona-option">
                            <input type="radio" name="persona" value="listener_creator">
                            <span class="auth-persona-card">
                                <strong>Pendengar / Kreator Audio</strong>
                                <span>Cocok kalau fokusnya menikmati audio atau mulai bikin podcast, audio story, dan audiobook.</span>
                            </span>
                        </label>
                    </div>
                </div>
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
                    Gratis daftar. Masuk dulu, nanti mau baca, bikin karya, atau share karya bisa dipilih dari dalam.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  function resolveMemberHome(roles = [], fallback = '/explore') {
    return DK.memberHomeFromRoles(roles, fallback);
  }

  async function redirectAuthenticatedSession() {
    if (!DK.token()) return;

    const me = await DK.get('/auth/me');
    if (!me?.user?.id) {
      DK.clearToken();
      return;
    }

    location.href = resolveMemberHome(me.roles, '/explore');
  }

  async function doRegister() {
    const msg = document.querySelector('#msg');
    const persona = document.querySelector('input[name="persona"]:checked')?.value || 'reader';
    const body = ['name','email','phone','password','password_confirmation']
      .reduce((o,k)=>(o[k]=document.querySelector('#'+k).value,o),{ persona });
    const { ok, data } = await DK.post('/auth/register', body);
    if (ok) {
      DK.setToken(data.token);
      msg.innerHTML = '<div class="alert alert-success">Akun dibuat! Mengalihkan…</div>';
      const redirectTarget = data.redirect_to || resolveMemberHome(data.roles, '/explore');
      setTimeout(() => location.href = redirectTarget, 800);
    } else {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal mendaftar.');
      msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
    }
  }

  redirectAuthenticatedSession();
</script>
@endpush
