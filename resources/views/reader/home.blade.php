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
                <p>Beranda saya padatkan agar pembaca lebih cepat menemukan karya yang layak dicoba, dilanjutkan, atau langsung dijelajahi.</p>
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
                    <a href="#home-free-start" class="home-genre-pill">Mulai Gratis</a>
                    <a href="#home-continue" class="home-genre-pill">Enak Buat Lanjut</a>
                    <a href="#home-creator" class="home-genre-pill">Kreator</a>
                </div>
                <div class="hero-proof home-hero-proof">
                    <div class="proof-item">
                        <strong>Lebih content-first</strong>
                        <span>Rak karya saya dorong lebih depan agar alasan untuk klik muncul lebih cepat daripada narasi promosi.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Lebih cepat dipindai</strong>
                        <span>Pembaca bisa langsung melihat apa yang ramai, apa yang gratis, dan cerita mana yang enak dilanjutkan.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase home-hero-showcase">
                <div class="hero-card hero-card-primary home-spotlight-card">
                    <span class="mini-label">Pilihan Cepat</span>
                    <h2>Begitu mendarat, pembaca langsung melihat rak yang hidup dan tahu harus masuk dari mana.</h2>
                    <p>Strukturnya saya sederhanakan: yang ramai dulu, yang baru terbit sesudahnya, lalu rak yang membantu pembaca mulai dan lanjut baca.</p>
                    <div class="home-quick-links">
                        <a href="{{ route('explore', ['trending' => 1]) }}" class="home-quick-link">Lihat yang lagi ramai</a>
                        <a href="#home-free-start" class="home-quick-link">Masuk rak gratis</a>
                        <a href="#home-continue" class="home-quick-link">Cari yang lanjut</a>
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
            <p class="home-section-copy">Rak pembuka untuk menunjukkan karya yang paling cepat menarik klik pertama.</p>
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
            <p class="home-section-copy">Begitu karya baru tayang, pembaca langsung melihat stok yang segar dan aktif.</p>
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
            <div class="showcase-panel home-showcase-panel" id="home-free-start">
                <div class="section-head">
                    <div>
                        <span class="section-kicker">Mulai Gratis</span>
                        <h2>Rak untuk pembaca yang ingin mencoba dulu tanpa banyak mikir.</h2>
                    </div>
                    <a href="{{ route('explore') }}">Buka explore</a>
                </div>
                <p class="home-section-copy">Karya dengan akses gratis saya kumpulkan di sini agar pembaca bisa masuk lebih ringan.</p>
                <div class="work-grid work-grid-premium home-work-grid" id="home-free-start-grid">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">✍️</div>
                        <p>Memuat karya yang bisa dicoba dulu…</p>
                    </div>
                </div>
            </div>

            <div class="showcase-panel home-showcase-panel" id="home-continue">
                <div class="section-head">
                    <div>
                        <span class="section-kicker">Enak Buat Lanjut</span>
                        <h2>Rak untuk cerita yang paling berpotensi bikin pembaca balik lagi.</h2>
                    </div>
                    <a href="{{ route('explore') }}">Lihat karya lain</a>
                </div>
                <p class="home-section-copy">Rak ini menonjolkan karya yang terasa punya momentum lanjut dan layak diikuti lebih lama.</p>
                <div class="work-grid work-grid-premium home-work-grid" id="home-continue-grid">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">📖</div>
                        <p>Memuat bacaan yang enak diikuti…</p>
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
  const homeSourceCache = new Map();

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

  function setHomeShelfVisibility(sectionSelector, isVisible) {
    if (!sectionSelector) return;

    const section = document.querySelector(sectionSelector);
    if (!section) return;

    section.hidden = !isVisible;

    const stack = section.closest('.home-shelf-stack');
    const stackSection = stack?.closest('.section');

    if (!stack || !stackSection) return;

    const hasVisiblePanels = Array.from(stack.children).some((panel) => !panel.hidden);
    stackSection.hidden = !hasVisiblePanels;
  }

  function uniqueHomeWorks(items = []) {
    const seen = new Set();

    return items.filter((work) => {
      const id = Number(work?.id);
      if (!id || seen.has(id)) return false;

      seen.add(id);
      return true;
    });
  }

  function buildHomeQuery({ trending = 0, type = '', search = '' } = {}) {
    const query = new URLSearchParams();
    if (trending) query.set('trending', '1');
    if (type) query.set('type', type);
    if (search) query.set('search', search);
    return query.toString();
  }

  async function getHomePool(options = {}) {
    const cacheKey = buildHomeQuery(options);
    if (homeSourceCache.has(cacheKey)) {
      return homeSourceCache.get(cacheKey);
    }

    try {
      const query = buildHomeQuery(options);
      const json = await DK.get('/works' + (query ? `?${query}` : ''));
      const items = Array.isArray(json.data) ? json.data : [];
      const uniqueItems = uniqueHomeWorks(items);
      homeSourceCache.set(cacheKey, uniqueItems);
      return uniqueItems;
    } catch (_) {
      homeSourceCache.set(cacheKey, []);
      return [];
    }
  }

  function renderHomeShelf({ target, items = [], variant = 'compact-home', sectionSelector = '' } = {}) {
    const grid = document.querySelector(target);
    if (!grid) return [];

    if (!items.length) {
      grid.innerHTML = '';
      setHomeShelfVisibility(sectionSelector, false);
      return [];
    }

    grid.innerHTML = items.map((work) => DK.workCard(work, { variant })).join('');
    collectHomeWorkIds(items);
    rememberCreators(items);
    setHomeShelfVisibility(sectionSelector, true);
    return items;
  }

  function pickHomeShelfItems(sourceItems = [], {
    limit = 4,
    primaryFilter = () => true,
    secondaryFilter = null,
    allowSeenFallback = false,
  } = {}) {
    const picked = [];
    const pickedIds = new Set();
    const unseenItems = sourceItems.filter((work) => !homeSeenWorkIds.has(Number(work?.id)));

    function appendFrom(items, filterFn = () => true) {
      items.forEach((work) => {
        const id = Number(work?.id);
        if (!id || pickedIds.has(id) || picked.length >= limit) return;
        if (!filterFn(work)) return;

        picked.push(work);
        pickedIds.add(id);
      });
    }

    appendFrom(unseenItems, primaryFilter);

    if (picked.length < limit && secondaryFilter) {
      appendFrom(unseenItems, secondaryFilter);
    }

    if (picked.length < limit && allowSeenFallback) {
      appendFrom(sourceItems, primaryFilter);
    }

    if (picked.length < limit && secondaryFilter && allowSeenFallback) {
      appendFrom(sourceItems, secondaryFilter);
    }

    return picked.slice(0, limit);
  }

  async function loadHomeShelves() {
    const trendingPool = await getHomePool({ trending: 1 });
    const latestPool = await getHomePool();
    const discoveryPool = uniqueHomeWorks([...latestPool, ...trendingPool]);

    renderHomeShelf({
      target: '#home-trending-grid',
      items: pickHomeShelfItems(trendingPool, {
        limit: 6,
        primaryFilter: () => true,
      }),
      sectionSelector: '#home-trending .home-showcase-panel',
    });

    renderHomeShelf({
      target: '#home-latest-grid',
      items: pickHomeShelfItems(latestPool, {
        limit: 6,
        primaryFilter: () => true,
        allowSeenFallback: true,
      }),
      sectionSelector: '#home-latest .home-showcase-panel',
    });

    renderHomeShelf({
      target: '#home-free-start-grid',
      items: pickHomeShelfItems(discoveryPool, {
        limit: 4,
        primaryFilter: (work) => Number(work?.chapters_free_count ?? 0) > 0,
        secondaryFilter: (work) => Number(work?.published_chapters_count ?? 0) > 0,
        allowSeenFallback: true,
      }),
      sectionSelector: '#home-free-start',
    });

    renderHomeShelf({
      target: '#home-continue-grid',
      items: pickHomeShelfItems(discoveryPool, {
        limit: 4,
        primaryFilter: (work) => Number(work?.published_chapters_count ?? 0) > 1,
        secondaryFilter: (work) => Number(work?.views ?? 0) > 0 || Number(work?.chapters_free_count ?? 0) > 0,
        allowSeenFallback: true,
      }),
      sectionSelector: '#home-continue',
    });

    renderHomeCreators();
  }
  loadHomeShelves();
  DK.refreshCredit();
</script>
@endpush
