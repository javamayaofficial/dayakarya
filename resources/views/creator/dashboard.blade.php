@extends('layouts.app')
@section('title', 'Dashboard Kreator — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Creator Cockpit</span>
                <h1>Bangun katalog yang bernilai dan baca angkanya dengan tenang.</h1>
                <p>Dashboard ini memberi kontrol yang rapi untuk karya dan monetisasi.</p>
                <div class="creator-hero-actions">
                    <a href="#creator-quick-create" class="btn btn-gold">＋ Karya Baru</a>
                    <a href="{{ route('wallet') }}" class="btn btn-ghost">Tarik Penghasilan</a>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Monetization Ready</span>
                <h2>Bukan sekadar statistik, tetapi meja kendali kreator.</h2>
                <p>Karya, pembacaan, royalti, dan pengikut tersaji dalam satu panel.</p>
            </div>
        </div>

        <div class="creator-stat-grid">
            <div class="stat stat-feature">
                <div class="label">Total Karya</div>
                <div class="value" id="s-works">—</div>
                <p>Jumlah karya aktif dalam katalog Anda.</p>
            </div>
            <div class="stat gold stat-feature">
                <div class="label">Total Dibaca</div>
                <div class="value" id="s-views">—</div>
                <p>Gambaran awal daya tarik karya Anda.</p>
            </div>
            <div class="stat teal stat-feature">
                <div class="label">Royalti (Rupiah)</div>
                <div class="value" id="s-royalty">—</div>
                <p>Ringkasan nilai yang sudah masuk ke akun Anda.</p>
            </div>
            <div class="stat stat-feature">
                <div class="label">Pengikut</div>
                <div class="value" id="s-followers">—</div>
                <p>Basis audiens yang mengikuti perkembangan Anda.</p>
            </div>
        </div>

        <div class="creator-panel-grid">
            <div class="creator-panel card">
                <div class="creator-quick-create" id="creator-quick-create">
                    <div class="section-head section-head-premium">
                        <div>
                            <span class="section-kicker">Quick Create</span>
                            <h2>Terbitkan draft baru tanpa keluar dari dashboard</h2>
                        </div>
                    </div>
                    <div id="creator-msg"></div>
                    <div class="creator-form-grid">
                        <div class="field">
                            <label>Judul karya</label>
                            <input id="creator-title" placeholder="Contoh: Senja yang Datang Terlambat">
                        </div>
                        <div class="field">
                            <label>Tipe karya</label>
                            <select id="creator-type">
                                <option value="cerpen">Cerpen</option>
                                <option value="novel">Novel Berseri</option>
                                <option value="podcast">Podcast</option>
                                <option value="audio_story">Audio Story</option>
                                <option value="dongeng">Dongeng</option>
                                <option value="motivasi">Cerita Motivasi</option>
                                <option value="audiobook">Audiobook</option>
                            </select>
                        </div>
                        <div class="field" style="grid-column:1/-1">
                            <label>Sinopsis singkat</label>
                            <textarea id="creator-synopsis" rows="4" placeholder="Tulis ringkasan karya untuk menyimpan draft pertama Anda."></textarea>
                        </div>
                    </div>
                    <button class="btn btn-gold" id="creator-submit" onclick="createWork()">Simpan Sebagai Draft</button>
                </div>

                <div class="section-head section-head-premium">
                    <div>
                        <span class="section-kicker">Katalog Kreator</span>
                        <h2>Karyaku</h2>
                    </div>
                </div>
                <div class="work-grid work-grid-premium" id="my-works">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">🖋️</div>
                        <h3>Belum ada karya</h3>
                        <p>Mulai terbitkan karya pertama Anda dengan presentasi yang rapi.</p>
                        <a href="#creator-quick-create" class="btn btn-gold">Buat Karya Pertama</a>
                    </div>
                </div>
            </div>

            <aside class="creator-side">
                <div class="creator-side-card">
                    <span class="section-kicker">Prioritas Berikutnya</span>
                    <h3>Naikkan nilai katalog, bukan sekadar stok konten.</h3>
                    <p>Fokus pada konsistensi, presentasi, dan alur premium.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft">
                    <span class="section-kicker">Monetisasi</span>
                    <h3>Royalti, wallet, dan affiliate bekerja lebih baik saat katalog terasa bernilai.</h3>
                    <p>Gunakan dashboard ini untuk menentukan apa yang perlu dipromosikan.</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  if (!DK.token()) location.href = '/masuk';

  function escapeHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function creatorCard(work) {
    const statusLabel = {
      draft: 'Draft',
      review: 'Menunggu review',
      published: 'Tayang',
      rejected: 'Perlu revisi',
    }[work.status] ?? work.status;

    return `
      <article class="work-card work-card-premium">
        <div class="card-cover">
          <div class="card-badge">${escapeHtml(statusLabel)}</div>
        </div>
        <div class="card-body">
          <div class="eyebrow">${escapeHtml(work.category?.name ?? 'Tanpa kategori')} · ${escapeHtml((work.type || '').replace('_', ' '))}</div>
          <h3>${escapeHtml(work.title)}</h3>
          <div class="work-meta">
            <span>${(work.views ?? 0).toLocaleString('id-ID')} views</span>
            <span>${(work.likes_count ?? 0).toLocaleString('id-ID')} suka</span>
          </div>
          <div class="work-meta">${work.published_at ? new Date(work.published_at).toLocaleDateString('id-ID') : 'Belum dipublikasikan'}</div>
        </div>
      </article>
    `;
  }

  function renderCreatorEmptyState() {
    document.querySelector('#my-works').innerHTML = `
      <div class="state" style="grid-column:1/-1">
        <div class="emoji">🖋️</div>
        <h3>Belum ada karya</h3>
        <p>Mulai terbitkan karya pertama Anda dengan presentasi yang rapi.</p>
        <a href="#creator-quick-create" class="btn btn-gold">Buat Karya Pertama</a>
      </div>`;
  }

  async function loadCreatorDashboard() {
    try {
      const data = await DK.get('/creator/dashboard');
      document.querySelector('#s-works').textContent = (data.stats?.works ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-views').textContent = (data.stats?.views ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-royalty').textContent = 'Rp' + (data.stats?.royalty_rupiah ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-followers').textContent = (data.stats?.followers ?? 0).toLocaleString('id-ID');

      const works = data.works ?? [];
      if (!works.length) {
        renderCreatorEmptyState();
        return;
      }

      document.querySelector('#my-works').innerHTML = works.map(creatorCard).join('');
    } catch (error) {
      document.querySelector('#creator-msg').innerHTML = '<div class="alert alert-error">Dashboard kreator belum berhasil dimuat. Silakan masuk ulang lalu coba lagi.</div>';
      document.querySelector('#my-works').innerHTML = `
        <div class="state" style="grid-column:1/-1">
          <div class="emoji">⚠️</div>
          <h3>Dashboard belum berhasil dimuat</h3>
          <p>Data karya dan statistik belum bisa ditampilkan saat ini. Coba muat ulang halaman atau masuk kembali ke akun Anda.</p>
        </div>`;
    }
  }

  async function createWork() {
    const button = document.querySelector('#creator-submit');
    const msg = document.querySelector('#creator-msg');
    button.disabled = true;
    msg.innerHTML = '';

    const { ok, data } = await DK.post('/works', {
      title: document.querySelector('#creator-title').value,
      type: document.querySelector('#creator-type').value,
      synopsis: document.querySelector('#creator-synopsis').value,
    });

    button.disabled = false;

    if (!ok) {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Draft belum berhasil dibuat.');
      msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
      return;
    }

    msg.innerHTML = '<div class="alert alert-success">Draft karya berhasil dibuat dan langsung masuk ke katalog kreator Anda.</div>';
    document.querySelector('#creator-title').value = '';
    document.querySelector('#creator-synopsis').value = '';
    loadCreatorDashboard();
  }

  loadCreatorDashboard();
</script>
@endpush
