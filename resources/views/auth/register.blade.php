@extends('layouts.app')
@section('title', 'Daftar — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Mulai Dengan Standar yang Lebih Tinggi</span>
                <h1>Bergabung ke Dayakarya untuk membangun karya, audiens, dan pendapatan dengan citra yang lebih kuat.</h1>
                <p>Baik Anda kreator, penikmat karya, affiliate, sponsor, maupun CSR, Dayakarya disiapkan sebagai ekosistem yang membantu setiap peran tampil lebih rapi, lebih serius, dan lebih siap bertumbuh.</p>
                <div class="auth-points">
                    <div class="auth-point">
                        <strong>Untuk kreator yang ingin dihargai lebih tinggi</strong>
                        <span>Terbitkan karya dengan presentasi yang lebih premium dan monetisasi yang terasa lebih pantas dibayar.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Untuk penikmat karya yang lebih selektif</strong>
                        <span>Nikmati pengalaman membaca dan mendengar yang lebih nyaman, tertata, dan terasa lebih bernilai.</span>
                    </div>
                    <div class="auth-point">
                        <strong>Untuk kolaborator yang butuh kredibilitas</strong>
                        <span>Affiliate, sponsor, dan program CSR bisa tumbuh dalam satu ruang tanpa kehilangan kesan profesional.</span>
                    </div>
                </div>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Buat Akun Dayakarya</span>
                    <h2>Mulai gratis hari ini</h2>
                    <p>Buat akun untuk mulai menerbitkan karya, menikmati katalog, atau membangun kolaborasi di ruang yang lebih bernilai.</p>
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
                    <a href="/masuk">Masuk sekarang</a>
                </div>
                <div class="auth-note">
                    Pendaftaran gratis, tetapi fondasinya disiapkan untuk perjalanan kreator yang ingin tumbuh dengan lebih serius dan lebih berkelas.
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
