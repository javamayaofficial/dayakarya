@extends('layouts.app')
@section('title', 'Leaderboard — Dayakarya')
@section('body_class', 'page-leaderboard')

@section('content')
<section class="section">
    <div class="container leaderboard-container">
        <div class="leaderboard-hero">
            <div class="leaderboard-hero-copy">
                <span class="section-kicker">Leaderboard Dayakarya</span>
                <h1>Lihat karya dan kreator yang paling diperhatikan.</h1>
                <p>Leaderboard menampilkan siapa yang sedang naik dan siapa yang paling kuat menarik audiens.</p>
            </div>
            <div class="leaderboard-hero-note">
                <span class="mini-label">Signal of Growth</span>
                <h2>Bukan sekadar ranking, tetapi signal pertumbuhan.</h2>
                <p>Skor dibangun dari views, apresiasi, pengikut, karya, dan royalti.</p>
            </div>
        </div>

        <div class="leaderboard-summary-grid" id="leaderboard-summary">
            <div class="stat stat-feature">
                <div class="label">Karya Published</div>
                <div class="value">—</div>
                <p>Karya yang sudah masuk radar ranking.</p>
            </div>
            <div class="stat gold stat-feature">
                <div class="label">Kreator Aktif</div>
                <div class="value">—</div>
                <p>Kreator aktif yang ikut membentuk persaingan katalog.</p>
            </div>
            <div class="stat teal stat-feature">
                <div class="label">Total Views</div>
                <div class="value">—</div>
                <p>Total perhatian audiens pada karya yang tayang.</p>
            </div>
            <div class="stat stat-feature">
                <div class="label">Total Royalti</div>
                <div class="value">—</div>
                <p>Nilai yang sudah mengalir ke kreator.</p>
            </div>
        </div>

        <div class="leaderboard-meta" id="leaderboard-meta">
            <div class="meta-card">
                <strong>Formula Karya</strong>
                <span>Memuat dasar perhitungan leaderboard karya.</span>
            </div>
            <div class="meta-card">
                <strong>Formula Kreator</strong>
                <span>Memuat dasar perhitungan leaderboard kreator.</span>
            </div>
        </div>

        <div class="section-head section-head-premium leaderboard-head">
            <div>
                <span class="section-kicker">Top Karya</span>
                <h2>Karya yang paling kuat menarik perhatian</h2>
            </div>
        </div>
        <div class="leaderboard-list leaderboard-list-works" id="leaderboard-works">
            <div class="state" style="grid-column:1/-1">
                <div class="emoji">🏆</div>
                <p>Memuat leaderboard karya…</p>
            </div>
        </div>

        <div class="section-head section-head-premium leaderboard-head">
            <div>
                <span class="section-kicker">Top Kreator</span>
                <h2>Kreator yang paling konsisten membangun nilai</h2>
            </div>
        </div>
        <div class="leaderboard-list leaderboard-list-creators" id="leaderboard-creators">
            <div class="state" style="grid-column:1/-1">
                <div class="emoji">📈</div>
                <p>Memuat leaderboard kreator…</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  function escapeLeaderboardHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function formatCompactNumber(value) {
    return Number(value || 0).toLocaleString('id-ID');
  }

  function leaderboardWorkCard(work, index) {
    const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : `#${index + 1}`;
    const creator = escapeLeaderboardHtml(work.creator?.name ?? 'Kreator Dayakarya');
    const category = escapeLeaderboardHtml(work.category?.name ?? 'Tanpa kategori');
    const title = escapeLeaderboardHtml(work.title);
    const type = escapeLeaderboardHtml(DK.typeLabel(work.type));
    const score = formatCompactNumber(work.leaderboard_score ?? 0);
    const views = formatCompactNumber(work.views ?? 0);
    const likes = formatCompactNumber(work.likes_count ?? 0);
    const coverStyle = work.cover
      ? `background-image:url('${encodeURI(work.cover)}');background-size:cover;background-position:center;`
      : '';

    return `
      <a class="leaderboard-card leaderboard-work-card" href="/karya/${escapeLeaderboardHtml(work.slug)}">
        <div class="leaderboard-rank">${medal}</div>
        <div class="leaderboard-cover" style="${coverStyle}">
          <span class="type-tag">${type}</span>
        </div>
        <div class="leaderboard-card-body">
          <div class="eyebrow">${category} · ${creator}</div>
          <h3>${title}</h3>
          <div class="leaderboard-stats">
            <span>Skor ${score}</span>
            <span>${views} views</span>
            <span>${likes} suka</span>
          </div>
        </div>
      </a>`;
  }

  function leaderboardCreatorCard(creator, index) {
    const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : `#${index + 1}`;
    const name = escapeLeaderboardHtml(creator.name ?? 'Kreator');
    const username = creator.username ? '@' + escapeLeaderboardHtml(creator.username) : 'Creator Dayakarya';
    const bio = escapeLeaderboardHtml(creator.bio || 'Membangun katalog yang tumbuh bersama audiens dan nilai karya.');
    const works = formatCompactNumber(creator.works_count ?? 0);
    const views = formatCompactNumber(creator.total_views ?? 0);
    const followers = formatCompactNumber(creator.total_followers ?? 0);
    const royalty = formatCompactNumber(creator.total_royalty ?? 0);
    const score = formatCompactNumber(creator.leaderboard_score ?? 0);

    return `
      <article class="leaderboard-card leaderboard-creator-card">
        <div class="leaderboard-rank">${medal}</div>
        <div class="leaderboard-card-body">
          <div class="eyebrow">${username}</div>
          <h3>${name}</h3>
          <p>${bio}</p>
          <div class="leaderboard-stats">
            <span>${works} karya</span>
            <span>${views} views</span>
            <span>${followers} followers</span>
            <span>Rp${royalty}</span>
            <span>Skor ${score}</span>
          </div>
        </div>
      </article>`;
  }

  function renderLeaderboardEmpty(target, emoji, title, message) {
    const el = document.querySelector(target);
    if (!el) return;
    el.innerHTML = `
      <div class="state" style="grid-column:1/-1">
        <div class="emoji">${emoji}</div>
        <h3>${title}</h3>
        <p>${message}</p>
      </div>`;
  }

  async function loadLeaderboard() {
    try {
      const data = await DK.get('/leaderboard');
      const summaryItems = document.querySelectorAll('#leaderboard-summary .stat');
      if (summaryItems.length >= 4) {
        summaryItems[0].querySelector('.value').textContent = formatCompactNumber(data.summary?.published_works ?? 0);
        summaryItems[1].querySelector('.value').textContent = formatCompactNumber(data.summary?.active_creators ?? 0);
        summaryItems[2].querySelector('.value').textContent = formatCompactNumber(data.summary?.total_views ?? 0);
        summaryItems[3].querySelector('.value').textContent = 'Rp' + formatCompactNumber(data.summary?.total_royalty ?? 0);
      }

      const meta = document.querySelectorAll('#leaderboard-meta .meta-card');
      if (meta.length >= 2) {
        meta[0].querySelector('span').textContent = data.meta?.works_formula ?? 'views + likes x 20';
        meta[1].querySelector('span').textContent = data.meta?.creators_formula ?? 'views + likes x 20 + followers x 40 + royalty/1000 + works x 80';
      }

      const works = data.top_works ?? [];
      if (!works.length) {
        renderLeaderboardEmpty('#leaderboard-works', '🏆', 'Leaderboard karya belum terisi', 'Begitu karya mulai dipublikasikan dan dikunjungi audiens, ranking ini akan terisi otomatis.');
      } else {
        document.querySelector('#leaderboard-works').innerHTML = works.map((work, index) => leaderboardWorkCard(work, index)).join('');
      }

      const creators = data.top_creators ?? [];
      if (!creators.length) {
        renderLeaderboardEmpty('#leaderboard-creators', '📈', 'Leaderboard kreator belum terisi', 'Begitu katalog kreator mulai aktif dan berkembang, ranking ini akan menampilkan para kreator yang paling menonjol.');
      } else {
        document.querySelector('#leaderboard-creators').innerHTML = creators.map((creator, index) => leaderboardCreatorCard(creator, index)).join('');
      }
    } catch (_) {
      renderLeaderboardEmpty('#leaderboard-works', '⚠️', 'Leaderboard belum berhasil dimuat', 'Periksa koneksi Anda lalu coba lagi dalam beberapa saat.');
      renderLeaderboardEmpty('#leaderboard-creators', '⚠️', 'Leaderboard belum berhasil dimuat', 'Periksa koneksi Anda lalu coba lagi dalam beberapa saat.');
    }
  }

  loadLeaderboard();
</script>
@endpush
