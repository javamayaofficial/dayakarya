@extends('layouts.app')
@section('title', 'Dashboard Member — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Area Member</span>
                <h1>Satu tempat untuk nikmati karya, bikin karya, dan mulai cari cuan.</h1>
                <p>Kalau mau nulis, podcast, atau bikin video series, mulainya dari sini. Kalau lagi cari bacaan, audio, atau tontonan yang bagus, semuanya juga ada di sini.</p>
                <div class="creator-hero-actions">
                    <a href="#creator-quick-create" class="btn btn-gold">＋ Karya Baru</a>
                    <a href="{{ route('wallet') }}" class="btn btn-ghost">Tarik Penghasilan</a>
                    <button type="button" class="btn btn-ghost" id="creator-logout" onclick="logoutCreator()">Keluar</button>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Satu Tempat</span>
                <h2>Masuk sekali, semua pintunya ada di sini.</h2>
                <p>Lihat perkembangan karya, cek penghasilan, atau bantu sebar karya yang kamu suka dari panel yang sama.</p>
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
                <div class="creator-production-guide">
                    <div class="creator-guide-card">
                        <span class="section-kicker">Alur Produksi</span>
                        <h3>Mulai dari draft yang jelas, lalu pikirkan pengalaman orang yang menikmatinya.</h3>
                        <p>Tujuannya bukan cuma karya tayang, tapi juga enak dibaca atau didengar sampai selesai.</p>
                    </div>
                    <div class="creator-guide-steps">
                        <div class="creator-guide-step">
                            <strong>1. Tentukan formatnya</strong>
                            <span>Pilih apakah karya ini cocok dinikmati sebagai bacaan atau audio.</span>
                        </div>
                        <div class="creator-guide-step">
                            <strong>2. Bikin pembukanya jelas</strong>
                            <span>Judul dan sinopsis harus bikin orang paham mereka akan menikmati karya seperti apa.</span>
                        </div>
                        <div class="creator-guide-step">
                            <strong>3. Jaga kenyamanan output</strong>
                            <span>Teks perlu ringan di mata. Audio perlu tenang di telinga. Fokusnya selalu ke karya yang dipilih.</span>
                        </div>
                    </div>
                </div>

                <div class="creator-quick-create" id="creator-quick-create">
                    <div class="section-head section-head-premium">
                        <div>
                            <span class="section-kicker">Quick Create</span>
                            <h2>Kalau sudah siap, bikin draft baru dari sini</h2>
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

                <div class="section-head section-head-premium">
                    <div>
                        <span class="section-kicker">Katalog Saya</span>
                        <h2>Karya Saya</h2>
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
                    <span class="section-kicker">Output Nyaman</span>
                    <h3>Bacaan enak di layar kecil. Audio enak didengar tanpa bikin capek.</h3>
                    <p>Pakai kalimat pembuka yang jelas, ritme yang rapi, dan jangan ganggu fokus orang dari karya yang sedang mereka pilih.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft">
                    <span class="section-kicker">Setelah Tayang</span>
                    <h3>Begitu karya tayang, cek lagi pengalaman pembaca dan pendengarnya.</h3>
                    <p>Lihat apakah halaman karya sudah langsung fokus ke isi, bagian yang dipilih, dan alurnya nyaman sampai selesai.</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
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

    const actionHref = `/creator/works/${work.id}`;
    const publicHref = work.slug ? `/karya/${work.slug}` : '';

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
          <div class="card-badge">${escapeHtml(publishState.badge)}</div>
        </div>
        <div class="card-body">
          <div class="eyebrow">${escapeHtml(work.category?.name ?? 'Tanpa kategori')} · ${escapeHtml(DK.typeLabel(work.type || ''))}</div>
          <h3>${escapeHtml(work.title)}</h3>
          <div class="work-meta">
            <span>${(work.views ?? 0).toLocaleString('id-ID')} views</span>
            <span>${(work.likes_count ?? 0).toLocaleString('id-ID')} suka</span>
          </div>
          <div class="work-meta">
            <span>${chapterCount} part</span>
            <span>${readyChapterCount} siap tayang</span>
          </div>
          <div class="work-meta">${work.published_at ? 'Tayang sejak ' + new Date(work.published_at).toLocaleDateString('id-ID') : 'Belum dipublikasikan'}</div>
          <div class="work-meta">${escapeHtml(publishState.note)}</div>
          <div class="work-card-footer">
            <a class="read-link" href="${actionHref}">${publishState.actionLabel}</a>
            ${publicHref ? `<a class="read-stat" href="${publicHref}">Lihat Tayang</a>` : `<span class="read-stat">${publishState.badge}</span>`}
          </div>
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

  async function loadCreatorDashboard() {
    const session = await ensureMemberSession();
    if (!session) return;

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
      document.querySelector('#creator-msg').innerHTML = '<div class="alert alert-error">Dashboard member belum berhasil dimuat. Silakan masuk ulang lalu coba lagi.</div>';
      document.querySelector('#my-works').innerHTML = `
        <div class="state" style="grid-column:1/-1">
          <div class="emoji">⚠️</div>
          <h3>Dashboard belum berhasil dimuat</h3>
          <p>Data karya dan statistik belum bisa ditampilkan saat ini. Coba muat ulang halaman atau masuk kembali ke akun kamu.</p>
        </div>`;
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
  loadCreatorDashboard();
</script>
@endpush
