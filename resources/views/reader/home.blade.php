@extends('layouts.app')

@section('title', 'Dayakarya - Berkarya, Berdampak, Berpenghasilan')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium home-hero-premium">
    <div class="container">
        <div class="hero-shell home-hero-shell">
            <div class="hero-copy home-hero-copy">
                <span class="eyebrow">Homepage Baru Dayakarya</span>
                <h1>Etalase karya yang terasa <em>hidup</em>, ramai, dan bikin orang ingin lanjut baca.</h1>
                <p>Beranda Dayakarya saya arahkan seperti marketplace bacaan modern: karya unggulan langsung terlihat, rak genre lebih cepat dipindai, dan pembaca bisa segera menemukan cerita yang layak dibuka.</p>
                <div class="hero-actions">
                    <a href="{{ route('explore') }}" class="btn btn-gold">Jelajahi Cerita</a>
                    <a href="{{ route('register') }}" class="btn btn-ghost">Mulai Berkarya</a>
                    <button type="button" class="btn btn-install" data-install-app>
                        <span class="btn-install-ic">↓</span>
                        <span data-install-label>Install App</span>
                    </button>
                </div>
                <p class="install-note" data-install-note>Pasang Dayakarya biar bukanya lebih cepat dan nyaman, seperti aplikasi.</p>
                <div class="home-genre-rail">
                    <a href="#home-trending" class="home-genre-pill">Sedang Ramai</a>
                    <a href="#home-latest" class="home-genre-pill">Baru Terbit</a>
                    <a href="#home-cerpen" class="home-genre-pill">Cerpen</a>
                    <a href="#home-novel" class="home-genre-pill">Novel Berseri</a>
                    <a href="#home-creator" class="home-genre-pill">Kreator</a>
                </div>
                <div class="hero-proof home-hero-proof">
                    <div class="proof-item">
                        <strong>Lebih content-first</strong>
                        <span>Karya populer, rak genre, dan pilihan editor tampil lebih dominan daripada copy promosi yang panjang.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Lebih cepat dipindai</strong>
                        <span>Pembaca bisa langsung melihat apa yang sedang ramai, apa yang gratis, dan karya mana yang layak dicoba dulu.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Lebih siap dikonversi</strong>
                        <span>Saat karya premium makin banyak, homepage ini sudah lebih siap mendorong explore, unlock, top up, dan balik baca.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase home-hero-showcase">
                <div class="hero-card hero-card-primary home-spotlight-card">
                    <span class="mini-label">Pilihan Cepat</span>
                    <h2>Begitu mendarat, pembaca langsung melihat rak yang hidup, bukan dibiarkan menebak ada apa di Dayakarya.</h2>
                    <p>Saya padatkan home ini seperti bookshelf modern: ada rak ramai, rak baru terbit, rak genre, lalu pintu masuk ke kreator.</p>
                    <div class="home-quick-links">
                        <a href="{{ route('explore', ['trending' => 1]) }}" class="home-quick-link">Lihat yang lagi ramai</a>
                        <a href="{{ route('explore', ['type' => 'cerpen']) }}" class="home-quick-link">Masuk rak cerpen</a>
                        <a href="{{ route('explore', ['type' => 'novel']) }}" class="home-quick-link">Masuk rak novel</a>
                    </div>
                </div>
                <div class="hero-stats home-hero-stats">
                    <div class="stat-tile">
                        <span class="label">Rak 01</span>
                        <strong>Sedang Ramai untuk karya yang paling cepat menarik klik pertama.</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Rak 02</span>
                        <strong>Baru Terbit untuk memberi rasa stok karya aktif dan terus bertambah.</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Rak 03</span>
                        <strong>Kreator Spotlight untuk membuat nama kreator ikut hidup di homepage.</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="home-trending">
    <div class="container">
        <div class="showcase-panel home-showcase-panel">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Sedang Ramai</span>
                    <h2>Rak utama yang langsung menunjukkan karya paling cepat menarik perhatian.</h2>
                </div>
                <a href="{{ route('explore', ['trending' => 1]) }}">Buka explore</a>
            </div>
            <p class="home-section-copy">Bagian ini saya posisikan seperti shelf populer di aplikasi baca: begitu landing, pembaca langsung tahu apa yang sedang aktif dibaca dan layak diklik duluan.</p>
            <div class="work-grid work-grid-premium home-work-grid" id="home-trending-grid">
                <div class="state" style="grid-column:1/-1">
                    <div class="emoji">📚</div>
                    <p>Memuat karya yang sedang ramai…</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="home-latest">
    <div class="container">
        <div class="showcase-panel home-showcase-panel home-showcase-emphasis">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Baru Terbit</span>
                    <h2>Rak ini bikin homepage terasa terus bergerak, bukan katalog yang diam.</h2>
                </div>
                <a href="{{ route('explore') }}">Lihat yang terbaru</a>
            </div>
            <p class="home-section-copy">Begitu karya baru tayang, pembaca bisa langsung melihat stok yang segar. Ini membantu Dayakarya terasa aktif walau jumlah katalog belum sebesar marketplace besar.</p>
            <div class="work-grid work-grid-premium home-work-grid" id="home-latest-grid">
                <div class="state" style="grid-column:1/-1">
                    <div class="emoji">✨</div>
                    <p>Memuat karya terbaru…</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="home-shelf-stack">
            <div class="showcase-panel home-showcase-panel" id="home-cerpen">
                <div class="section-head">
                    <div>
                        <span class="section-kicker">Cerpen Pilihan</span>
                        <h2>Bacaan singkat yang cepat memancing klik dan rasa penasaran.</h2>
                    </div>
                    <a href="{{ route('explore', ['type' => 'cerpen']) }}">Lihat semua</a>
                </div>
                <div class="work-grid work-grid-premium home-work-grid" id="home-cerpen-grid">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">✍️</div>
                        <p>Memuat cerpen pilihan…</p>
                    </div>
                </div>
            </div>

            <div class="showcase-panel home-showcase-panel" id="home-novel">
                <div class="section-head">
                    <div>
                        <span class="section-kicker">Novel Berseri</span>
                        <h2>Rak untuk cerita yang paling pas dibangun jadi kebiasaan balik baca.</h2>
                    </div>
                    <a href="{{ route('explore', ['type' => 'novel']) }}">Lihat semua</a>
                </div>
                <div class="work-grid work-grid-premium home-work-grid" id="home-novel-grid">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">📖</div>
                        <p>Memuat novel berseri…</p>
                    </div>
                </div>
            </div>

            <div class="showcase-panel home-showcase-panel" id="home-audio">
                <div class="section-head">
                    <div>
                        <span class="section-kicker">Audio & Dongeng</span>
                        <h2>Rak untuk karya yang nyaman dikonsumsi sambil bergerak, santai, atau sebelum tidur.</h2>
                    </div>
                    <a href="{{ route('explore', ['type' => 'dongeng']) }}">Lihat semua</a>
                </div>
                <div class="work-grid work-grid-premium home-work-grid" id="home-audio-grid">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">🎧</div>
                        <p>Memuat karya audio pilihan…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="home-creator">
    <div class="container">
        <div class="showcase-panel home-showcase-panel">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Kreator Spotlight</span>
                    <h2>Nama kreator ikut saya hidupkan, supaya homepage terasa seperti ekosistem karya, bukan rak anonim.</h2>
                </div>
                <a href="{{ route('register') }}">Jadi kreator</a>
            </div>
            <p class="home-section-copy">Saya ambil kreator yang muncul dari rak-rak di atas lalu tampilkan lagi sebagai spotlight ringan. Tujuannya sederhana: pembaca mulai mengingat orang di balik karya, bukan cuma judulnya.</p>
            <div class="home-creator-spotlight" id="home-creator-spotlight">
                <div class="state" style="grid-column:1/-1">
                    <div class="emoji">🪄</div>
                    <p>Menyusun spotlight kreator…</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Langkah Berikutnya</span>
                <h2>Homepage Dayakarya sekarang saya arahkan jadi perpaduan antara brand premium dan shelf discovery seperti aplikasi bacaan modern.</h2>
                <p>Hasil akhirnya bukan meniru KBM mentah-mentah, tetapi mengambil logika yang paling kuat: karya duluan, genre jelas, momentum terasa, dan jalur ke explore lebih cepat.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('explore') }}" class="btn btn-gold">Lihat Beranda Baru</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Mulai Isi Rak Karyamu</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const homeCreatorMap = new Map();
  const homeSeenWorkIds = new Set();

  function collectHomeWorkIds(items = []) {
    items.forEach((work) => {
      if (work?.id) {
        homeSeenWorkIds.add(Number(work.id));
      }
    });
  }

  function rememberCreators(items = []) {
    items.forEach((work) => {
      const creatorId = work.creator?.id || work.creator?.name;
      if (!creatorId || !work.creator?.name) return;

      const current = homeCreatorMap.get(creatorId) || {
        name: work.creator.name,
        avatar: work.creator?.avatar || '',
        workCount: 0,
        types: new Set(),
        titles: new Set(),
      };

      current.workCount += 1;
      if (!current.avatar && work.creator?.avatar) {
        current.avatar = work.creator.avatar;
      }
      if (work.type) current.types.add(DK.typeLabel(work.type));
      if (work.title) current.titles.add(work.title);
      homeCreatorMap.set(creatorId, current);
    });
  }

  function renderHomeCreators() {
    const spotlight = document.querySelector('#home-creator-spotlight');
    if (!spotlight) return;

    const items = Array.from(homeCreatorMap.values())
      .sort((a, b) => b.workCount - a.workCount)
      .slice(0, 4);

    if (!items.length) {
      spotlight.innerHTML = `<div class="state" style="grid-column:1/-1">
        <div class="emoji">✍️</div>
        <p>Kreator spotlight akan muncul seiring rak karya mulai terisi lebih ramai.</p>
      </div>`;
      return;
    }

    spotlight.innerHTML = items.map((creator, index) => {
      const typeList = Array.from(creator.types).slice(0, 2).join(' • ') || 'Karya pilihan';
      const featuredTitle = Array.from(creator.titles)[0] || 'Karya pilihan di Dayakarya';
      const avatarMarkup = creator.avatar
        ? `<img src="${creator.avatar}" alt="${creator.name}" class="home-creator-avatar">`
        : `<span class="home-creator-avatar home-creator-avatar-fallback">${creator.name.slice(0, 1).toUpperCase()}</span>`;
      return `
        <article class="home-creator-card">
          <div class="home-creator-head">
            ${avatarMarkup}
            <div>
              <span class="home-creator-rank">Spotlight ${index + 1}</span>
              <h3>${creator.name}</h3>
            </div>
          </div>
          <p>${typeList}</p>
          <p class="home-creator-featured">Sering muncul lewat <strong>${featuredTitle}</strong>.</p>
          <div class="home-creator-meta">
            <span>${creator.workCount} karya tampil di homepage</span>
            <span>Siap dijelajahi pembaca</span>
          </div>
          <a href="{{ route('explore') }}" class="home-creator-link">Lihat karya di explore</a>
        </article>
      `;
    }).join('');
  }

  async function loadUniqueShelf(options) {
    const items = await DK.loadWorks({
      ...options,
      excludeIds: Array.from(homeSeenWorkIds),
    });

    collectHomeWorkIds(items);
    rememberCreators(items);
    return items;
  }

  async function loadHomeShelves() {
    await loadUniqueShelf({
      trending: 1,
      target: '#home-trending-grid',
      variant: 'compact-home',
      limit: 6,
    });

    await loadUniqueShelf({
      target: '#home-latest-grid',
      variant: 'compact-home',
      limit: 6,
    });

    await loadUniqueShelf({
      type: 'cerpen',
      target: '#home-cerpen-grid',
      variant: 'compact-home',
      limit: 4,
    });

    await loadUniqueShelf({
      type: 'novel',
      target: '#home-novel-grid',
      variant: 'compact-home',
      limit: 4,
      emptyCopy: `<div class="state" style="grid-column:1/-1">
        <div class="emoji">📖</div><h3>Rak novel belum seramai yang lain</h3>
        <p>Begitu novel berseri mulai bertambah, rak ini akan jadi ruang balik baca paling penting.</p></div>`,
    });

    await loadUniqueShelf({
      type: 'dongeng',
      target: '#home-audio-grid',
      variant: 'compact-home',
      limit: 4,
      emptyCopy: `<div class="state" style="grid-column:1/-1">
        <div class="emoji">🎧</div><h3>Rak audio masih menunggu isi</h3>
        <p>Area ini sudah disiapkan untuk dongeng, audio story, dan karya dengar lainnya.</p></div>`,
    });

    renderHomeCreators();
  }
  loadHomeShelves();
  DK.refreshCredit();
</script>
@endpush
