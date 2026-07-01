@extends('layouts.app')
@section('title', 'Daftar — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Mulai dari fondasi yang tepat</span>
                <h1>Bergabung ke Dayakarya untuk membangun karya, komunitas, dan pendapatan dengan lebih terarah.</h1>
                <p>Baik Anda kreator, pembaca, pendengar, affiliate, sponsor, maupun CSR, Dayakarya disiapkan sebagai ekosistem yang terasa lebih matang dan siap tumbuh.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Untuk kreator</strong>
                        <span>Terbitkan karya, bangun citra yang lebih premium, dan monetisasi dengan alur yang lebih jelas.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Untuk penikmat karya</strong>
                        <span>Temukan pengalaman membaca dan mendengar yang lebih nyaman, rapi, dan bernilai.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Untuk kolaborator</strong>
                        <span>Affiliate, sponsor, dan program CSR bisa tumbuh di ruang yang sama tanpa kehilangan profesionalitas.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Buat Akun Dayakarya</span>
                    <h2>Mulai gratis hari ini</h2>
                    <p>Buat akun untuk mulai berkarya, menikmati karya, atau membangun kolaborasi yang lebih bernilai.</p>
                </div>

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
                <div class="auth-meta">
                    <span>Sudah punya akun?</span>
                    <a href="/masuk">Masuk</a>
                </div>
                <div class="auth-note">
                    Pendaftaran gratis dan disiapkan untuk perjalanan kreator yang lebih serius serta berkelanjutan.
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
