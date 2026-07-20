@extends('layouts.app')
@section('title', 'Dashboard Member — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Pusat Kerja Kreator</span>
                <h1>Bikin, rapikan, lalu tayangkan karya tanpa muter-muter.</h1>
                <p>Dashboard ini saya rapikan supaya kamu lebih cepat tahu harus mulai dari mana, draft mana yang perlu dibereskan, dan karya mana yang sudah siap didorong tayang.</p>
                <div class="creator-hero-pills">
                    <span class="creator-hero-pill">Mulai draft baru</span>
                    <span class="creator-hero-pill">Pantau progres karya</span>
                    <span class="creator-hero-pill">Cek hasil tayang</span>
                </div>
                <div class="creator-hero-actions">
                    <a href="#creator-quick-create" class="btn btn-gold">＋ Karya Baru</a>
                    <a href="#my-works" class="btn btn-ghost">Lihat Karya Saya</a>
                    <a href="{{ route('wallet') }}" class="btn btn-ghost">Wallet & Credit</a>
                    <button type="button" class="btn btn-ghost" id="creator-logout" onclick="logoutCreator()">Keluar</button>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Hari Ini</span>
                <h2>Kerjakan yang paling dekat ke tayang dulu.</h2>
                <p>Urutan yang paling nyaman: mulai draft seperlunya, isi part aktif sampai layak, lalu cek checklist sebelum publish.</p>
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

        <div class="creator-priority-bar">
            <div class="creator-priority-card">
                <span class="section-kicker">Fokus Draft</span>
                <strong id="creator-draft-focus">Belum ada data draft.</strong>
                <p id="creator-draft-focus-copy">Buat draft baru atau buka draft lama untuk lanjut produksi inti karya.</p>
            </div>
            <div class="creator-priority-card creator-priority-card-highlight">
                <span class="section-kicker">Siap Tayang</span>
                <strong id="creator-ready-focus">Checklist tayang akan muncul di sini.</strong>
                <p id="creator-ready-focus-copy">Begitu ada karya yang nyaris siap, dashboard ini akan kasih sinyal prioritasnya.</p>
            </div>
            <div class="creator-priority-card">
                <span class="section-kicker">Karya Live</span>
                <strong id="creator-live-focus">Belum ada karya tayang.</strong>
                <p id="creator-live-focus-copy">Begitu ada karya live, kamu bisa langsung lanjut cek performanya dari sini.</p>
            </div>
        </div>

        <div class="creator-panel-grid">
            <div class="creator-panel card">
                <div class="creator-quick-create" id="creator-quick-create">
                    <div class="section-head section-head-premium">
                        <div>
                            <span class="section-kicker">Quick Create</span>
                            <h2>Mulai draft baru tanpa pindah halaman</h2>
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
                            <select id="creator-type" onchange="updateProductionHint()">
                                <option value="cerpen">Cerpen</option>
                                <option value="novel">Novel Berseri</option>
                                <option value="podcast">Podcast</option>
                                <option value="audio_story">Audio Story</option>
                                <option value="video_series">Video Series</option>
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
                    <div class="creator-production-hint" id="creator-production-hint"></div>
                    <button class="btn btn-gold" id="creator-submit" onclick="createWork()">Simpan Draft & Mulai Produksi</button>
                </div>

                <div class="section-head section-head-premium creator-work-section-head">
                    <div>
                        <span class="section-kicker">Antrian Kerja</span>
                        <h2>Karya Saya</h2>
                        <p>Buka lagi draft yang belum aman, lanjutkan yang siap tayang, lalu cek hasil karya yang sudah live.</p>
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

                <div class="creator-production-guide creator-production-guide-compact">
                    <div class="creator-guide-card">
                        <span class="section-kicker">Alur Singkat</span>
                        <h3>Dashboard ini sekarang sengaja dibuat lebih operasional.</h3>
                        <p>Supaya kamu tidak tenggelam di penjelasan, cukup pegang tiga urutan ini setiap kali masuk.</p>
                    </div>
                    <div class="creator-guide-steps">
                        <div class="creator-guide-step">
                            <strong>1. Buat judul dan sinopsis seperlunya</strong>
                            <span>Tidak perlu sempurna dulu, yang penting punya draft dan arah karya.</span>
                        </div>
                        <div class="creator-guide-step">
                            <strong>2. Buka lagi karya yang paling dekat jadi</strong>
                            <span>Prioritaskan draft yang tinggal isi part, cover, atau cek publish checklist.</span>
                        </div>
                        <div class="creator-guide-step">
                            <strong>3. Setelah tayang, cek performa dan lanjutkan</strong>
                            <span>Begitu satu karya live, kembali ke dashboard untuk pilih langkah berikutnya.</span>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="creator-side">
                <div class="creator-side-card">
                    <span class="section-kicker">Pegangan Cepat</span>
                    <h3>Kalau bingung mau ngapain, kerjakan karya yang paling dekat ke tayang.</h3>
                    <p>Judul, sinopsis, isi part, lalu checklist. Kalau urutan ini rapi, pengalaman kreator terasa jauh lebih ringan.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft">
                    <span class="section-kicker">Setelah Publish</span>
                    <h3>Karya yang sudah live sebaiknya langsung dicek lagi dari sisi pembaca.</h3>
                    <p>Pastikan halaman karya enak dibuka, cover-nya kuat, dan pembukanya cukup bikin orang lanjut baca atau dengar.</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const dashboardState = {
    isLoading: false,
    lastLoadedAt: 0,
  };

  async function ensureMemberSession() {
    if (!DK.token()) {
      location.href = '/masuk';
      return null;
    }

    const me = await DK.get('/auth/me');
    if (!me?.user?.id) {
      DK.clearToken();
      location.href = '/masuk';
      return null;
    }

    return me;
  }

  function escapeHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function creatorCard(work) {
    const chapterCount = Number(work.chapters_count ?? 0);
    const readyChapterCount = Number(work.ready_chapters_count ?? 0);
    const publishedChapterCount = Number(work.published_chapters_count ?? 0);
    const likeCount = Number(work.likes_count ?? 0);
    const viewCount = Number(work.views ?? 0);

    const publishState = (() => {
      if (work.status === 'published') {
        return {
          badge: 'Sudah Tayang',
          actionLabel: 'Edit Lagi',
          note: publishedChapterCount > 0
            ? `${publishedChapterCount} part sudah tayang di katalog`
            : 'Karya ini sudah bisa dilihat akun lain',
          tone: 'live',
        };
      }

      if (work.status === 'rejected') {
        return {
          badge: 'Perlu Revisi',
          actionLabel: 'Lanjut Edit',
          note: 'Buka editor lalu rapikan lagi sebelum ditayangkan.',
          tone: 'revise',
        };
      }

      if (readyChapterCount > 0) {
        return {
          badge: 'Siap Tayang',
          actionLabel: 'Lanjut Edit',
          note: 'Masuk ke editor lalu klik Tayangkan Sekarang.',
          tone: 'ready',
        };
      }

      return {
        badge: 'Draft',
        actionLabel: 'Lanjut Edit',
        note: 'Isi minimal 1 part dulu supaya siap ditayangkan.',
        tone: 'draft',
      };
    })();

    const progressChips = [
      `<span class="creator-card-chip">${chapterCount} part</span>`,
      `<span class="creator-card-chip ${readyChapterCount > 0 ? 'is-highlight' : ''}">${readyChapterCount} siap tayang</span>`,
      `<span class="creator-card-chip">${viewCount.toLocaleString('id-ID')} views</span>`,
      `<span class="creator-card-chip">${likeCount.toLocaleString('id-ID')} suka</span>`,
    ].join('');

    const actionHref = `/creator/works/${work.id}`;
    const previewDraftHref = `/creator/works/${work.id}#creator-preview-card`;
    const publicHref = work.status === 'published' && work.slug ? `/karya/${work.slug}` : '';

    const coverUrl = String(work.cover_url ?? work.cover ?? '').trim();
    const safeCoverUrl = coverUrl
      ? encodeURI(coverUrl).replaceAll("'", '%27')
      : '';
    const coverStyle = safeCoverUrl
      ? `background-image:url('${safeCoverUrl}');background-size:cover;background-position:center;`
      : '';

    return `
      <article class="work-card work-card-premium">
        <div class="card-cover" style="${coverStyle}">
          <div class="card-badge card-badge-${publishState.tone}">${escapeHtml(publishState.badge)}</div>
        </div>
        <div class="card-body">
          <div class="eyebrow">${escapeHtml(work.category?.name ?? 'Tanpa kategori')} · ${escapeHtml(DK.typeLabel(work.type || ''))}</div>
          <h3>${escapeHtml(work.title)}</h3>
          <div class="creator-card-note">${escapeHtml(publishState.note)}</div>
          <div class="creator-card-progress">${progressChips}</div>
          <div class="work-meta">${work.published_at ? 'Tayang sejak ' + new Date(work.published_at).toLocaleDateString('id-ID') : 'Belum dipublikasikan'}</div>
          <div class="work-card-footer creator-card-footer">
            <a class="btn btn-primary creator-card-action" href="${actionHref}">${publishState.actionLabel}</a>
            ${publicHref
              ? `<a class="read-stat creator-card-secondary" href="${publicHref}">Lihat Tayang</a>`
              : `<a class="read-stat creator-card-secondary" href="${previewDraftHref}">Preview Draft</a>`}
          </div>
        </div>
      </article>
    `;
  }

  function updatePriorityBar(works = []) {
    const draftFocus = document.querySelector('#creator-draft-focus');
    const draftFocusCopy = document.querySelector('#creator-draft-focus-copy');
    const readyFocus = document.querySelector('#creator-ready-focus');
    const readyFocusCopy = document.querySelector('#creator-ready-focus-copy');
    const liveFocus = document.querySelector('#creator-live-focus');
    const liveFocusCopy = document.querySelector('#creator-live-focus-copy');

    if (!draftFocus || !draftFocusCopy || !readyFocus || !readyFocusCopy || !liveFocus || !liveFocusCopy) {
      return;
    }

    const draftWorks = works.filter((work) => work.status !== 'published');
    const readyWorks = works.filter((work) => Number(work.ready_chapters_count ?? 0) > 0);
    const liveWorks = works.filter((work) => work.status === 'published');

    if (!draftWorks.length) {
      draftFocus.textContent = 'Belum ada draft yang menunggu.';
      draftFocusCopy.textContent = 'Kalau ide baru muncul, bikin draft baru dari quick create lalu lanjutkan nanti di editor.';
    } else {
      draftFocus.textContent = `${draftWorks.length} draft masih butuh perhatian.`;
      draftFocusCopy.textContent = 'Buka lagi draft yang belum aman supaya progresnya tidak dingin terlalu lama.';
    }

    if (!readyWorks.length) {
      readyFocus.textContent = 'Belum ada karya yang dekat ke publish.';
      readyFocusCopy.textContent = 'Begitu satu part sudah rapi, dashboard ini akan menandai karya yang pantas diprioritaskan tayang.';
    } else {
      readyFocus.textContent = `${readyWorks.length} karya sudah dekat ke publish.`;
      readyFocusCopy.textContent = 'Prioritaskan karya yang sudah punya part siap tayang agar cepat berubah jadi katalog live.';
    }

    if (!liveWorks.length) {
      liveFocus.textContent = 'Belum ada karya live.';
      liveFocusCopy.textContent = 'Setelah karya pertama tayang, balik ke sini untuk cek performa dan tentukan kelanjutan part berikutnya.';
      return;
    }

    const liveViews = liveWorks.reduce((total, work) => total + Number(work.views ?? 0), 0);
    liveFocus.textContent = `${liveWorks.length} karya live dengan ${liveViews.toLocaleString('id-ID')} total views.`;
    liveFocusCopy.textContent = 'Karya yang sudah tayang sebaiknya dicek lagi dari sisi pembaca sambil lanjut menyiapkan update berikutnya.';
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

  function updateProductionHint() {
    const type = document.querySelector('#creator-type')?.value || 'cerpen';
    const hint = document.querySelector('#creator-production-hint');
    if (!hint) return;

    const guidance = {
      cerpen: {
        title: 'Mode baca',
        text: 'Cocok untuk pembaca yang ingin langsung masuk ke cerita. Pakai judul yang kuat, pembuka yang cepat, dan paragraf yang nyaman dibaca di HP.',
      },
      novel: {
        title: 'Mode baca berseri',
        text: 'Cocok untuk karya yang tumbuh pelan-pelan. Pastikan sinopsis memberi arah, lalu tiap bagian terasa bikin orang ingin lanjut.',
      },
      podcast: {
        title: 'Mode dengar',
        text: 'Cocok untuk pendengar. Pastikan opening tidak bertele-tele, ritmenya tenang, dan suara utamanya nyaman diikuti.',
      },
      audio_story: {
        title: 'Mode dengar cerita',
        text: 'Cocok untuk cerita audio yang imersif. Fokus ke alur yang gampang diikuti dan durasi yang tidak melelahkan telinga.',
      },
      video_series: {
        title: 'Mode nonton',
        text: 'Cocok untuk serial video yang lagi tren. Pastikan judulnya kuat, tiap episode jelas, dan tontonan utamanya tetap jadi fokus utama layar.',
      },
      dongeng: {
        title: 'Mode dongeng',
        text: 'Cocok untuk pengalaman yang hangat dan mudah dinikmati. Jaga bahasa tetap ringan, jelas, dan enak didengar.',
      },
      motivasi: {
        title: 'Mode inspirasi',
        text: 'Cocok untuk karya yang ingin memberi dorongan cepat. Langsung ke inti, hindari pembukaan yang terlalu panjang.',
      },
      audiobook: {
        title: 'Mode audiobook',
        text: 'Cocok untuk sesi dengar yang lebih lama. Pecah alurnya rapi dan jaga tempo supaya pendengar tetap betah.',
      },
    };

    const current = guidance[type] || guidance.cerpen;
    hint.innerHTML = `
      <strong>${current.title}</strong>
      <span>${current.text}</span>
    `;
  }

  async function loadCreatorDashboard(options = {}) {
    const { force = false } = options;
    if (dashboardState.isLoading) {
      return;
    }

    const now = Date.now();
    if (!force && dashboardState.lastLoadedAt && (now - dashboardState.lastLoadedAt) < 15000) {
      return;
    }

    dashboardState.isLoading = true;
    const session = await ensureMemberSession();
    if (!session) {
      dashboardState.isLoading = false;
      return;
    }

    try {
      const data = await DK.get('/creator/dashboard');
      document.querySelector('#s-works').textContent = (data.stats?.works ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-views').textContent = (data.stats?.views ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-royalty').textContent = 'Rp' + (data.stats?.royalty_rupiah ?? 0).toLocaleString('id-ID');
      document.querySelector('#s-followers').textContent = (data.stats?.followers ?? 0).toLocaleString('id-ID');

      const works = data.works ?? [];
      updatePriorityBar(works);
      if (!works.length) {
        renderCreatorEmptyState();
        return;
      }

      document.querySelector('#my-works').innerHTML = works.map(creatorCard).join('');
      dashboardState.lastLoadedAt = Date.now();
    } catch (error) {
      document.querySelector('#creator-msg').innerHTML = '<div class="alert alert-error">Dashboard member belum berhasil dimuat. Silakan masuk ulang lalu coba lagi.</div>';
      document.querySelector('#my-works').innerHTML = `
        <div class="state" style="grid-column:1/-1">
          <div class="emoji">⚠️</div>
          <h3>Dashboard belum berhasil dimuat</h3>
          <p>Data karya dan statistik belum bisa ditampilkan saat ini. Coba muat ulang halaman atau masuk kembali ke akun kamu.</p>
        </div>`;
    } finally {
      dashboardState.isLoading = false;
    }
  }

  async function logoutCreator() {
    const button = document.querySelector('#creator-logout');
    const msg = document.querySelector('#creator-msg');

    button.disabled = true;
    msg.innerHTML = '<div class="alert alert-success">Sedang keluar dari akun…</div>';

    await DK.logout();
    location.href = '/masuk';
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

    msg.innerHTML = '<div class="alert alert-success">Draft karya berhasil dibuat. Sekarang kamu sudah punya titik awal produksi yang bisa terus dirapikan sebelum tayang.</div>';
    setTimeout(() => {
      location.href = `/creator/works/${data.work.id}`;
    }, 450);
  }

  updateProductionHint();
  loadCreatorDashboard({ force: true });

  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
      loadCreatorDashboard();
    }
  });

  window.addEventListener('pageshow', () => {
    loadCreatorDashboard({ force: true });
  });
</script>
@endpush
