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
                    <a href="#home-cerpen" class="home-genre-pill">Cerpen</a>
                    <a href="#home-novel" class="home-genre-pill">Novel Berseri</a>
                    <a href="#home-audio" class="home-genre-pill">Audio</a>
                    <a href="#home-creator" class="home-genre-pill">Untuk Kreator</a>
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
                    <span class="mini-label">Spotlight Beranda</span>
                    <h2>Nuansanya saya geser ke arah rak cerita populer, bukan landing yang cuma bicara soal platform.</h2>
                    <p>Tujuannya supaya pembaca merasa Dayakarya punya stok karya yang hidup, sementara kreator merasa karya mereka tampil lebih layak dan lebih mudah ditemukan.</p>
                    <div class="home-spotlight-points">
                        <div class="home-spotlight-point">
                            <strong>Discovery lebih cepat</strong>
                            <span>Rak karya langsung terlihat dari atas halaman.</span>
                        </div>
                        <div class="home-spotlight-point">
                            <strong>Genre lebih jelas</strong>
                            <span>Cerpen, novel, dan audio punya shelf masing-masing.</span>
                        </div>
                        <div class="home-spotlight-point">
                            <strong>Lebih siap tumbuh</strong>
                            <span>Bisa menampung all-free sekarang dan premium nanti.</span>
                        </div>
                    </div>
                </div>
                <div class="hero-stats home-hero-stats">
                    <div class="stat-tile">
                        <span class="label">Rak 01</span>
                        <strong>Sedang Ramai untuk karya yang paling cepat menarik perhatian pembaca.</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Rak 02</span>
                        <strong>Genre Shelf untuk mempercepat browsing tanpa harus masuk explore dulu.</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Rak 03</span>
                        <strong>Creator Funnel untuk meyakinkan penulis bahwa Dayakarya lebih dari tempat upload biasa.</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="trust trust-premium">
            <div class="item"><span class="ic">◆</span> Tampilan lebih content-first</div>
            <div class="item"><span class="ic">◆</span> Rak genre lebih cepat discan</div>
            <div class="item"><span class="ic">◆</span> Cocok untuk karya gratis dan premium</div>
            <div class="item"><span class="ic">◆</span> Lebih siap mendorong explore dan unlock</div>
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
                <a href="{{ route('explore') }}">Buka explore</a>
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

<section class="section">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Arah Beranda</span>
                <h2>Bukan lagi landing generik, tapi discovery page yang lebih terasa seperti rumah bacaan modern.</h2>
            </div>
        </div>
        <div class="feature-grid home-direction-grid">
            <article class="feature-card home-direction-card">
                <span class="feature-icon">01</span>
                <h3>Genre jadi pintu masuk</h3>
                <p>Pembaca bisa lompat ke rak cerita favorit tanpa harus menyaring terlalu banyak dari awal.</p>
            </article>
            <article class="feature-card home-direction-card">
                <span class="feature-icon">02</span>
                <h3>Karya yang ramai terlihat duluan</h3>
                <p>Rak utama memberi rasa bahwa Dayakarya hidup, bukan sekadar tempat simpan karya statis.</p>
            </article>
            <article class="feature-card home-direction-card">
                <span class="feature-icon">03</span>
                <h3>Kreator lebih percaya diri</h3>
                <p>Homepage yang lebih editorial membuat karya terasa lebih layak ditemukan, dibaca, dan dipromosikan.</p>
            </article>
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
                    <a href="{{ route('explore') }}">Lihat semua</a>
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
                    <a href="{{ route('explore') }}">Lihat semua</a>
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
                    <a href="{{ route('explore') }}">Lihat semua</a>
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
                    <span class="section-kicker">Untuk Kreator</span>
                    <h2>Homepage ini juga saya siapkan agar karya kreator terasa lebih layak tampil di etalase publik.</h2>
                </div>
                <a href="{{ route('register') }}">Jadi kreator</a>
            </div>
            <div class="journey-grid home-creator-grid">
                <article class="journey-card">
                    <span class="journey-step">Etalase</span>
                    <h3>Karya lebih mudah dilihat dari atas halaman</h3>
                    <p>Begitu ada karya yang menarik, homepage baru ini lebih siap menaruhnya di rak populer, genre, atau shelf pilihan.</p>
                </article>
                <article class="journey-card">
                    <span class="journey-step">Monetisasi</span>
                    <h3>Lebih siap mendorong unlock saat premium makin aktif</h3>
                    <p>Struktur berandanya kini mendukung alur dari lihat karya, masuk ke detail, lalu lanjut ke unlock dan top up.</p>
                </article>
                <article class="journey-card">
                    <span class="journey-step">Komunitas</span>
                    <h3>Lebih cocok untuk event, leaderboard, dan spotlight kreator</h3>
                    <p>Bagian bawah homepage bisa tumbuh menjadi ruang komunitas tanpa merusak rak karya utama.</p>
                </article>
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
  DK.loadWorks({ trending: 1, target: '#home-trending-grid' });
  DK.loadWorks({ trending: 1, type: 'cerpen', target: '#home-cerpen-grid' });
  DK.loadWorks({ trending: 1, type: 'novel', target: '#home-novel-grid' });
  DK.loadWorks({ trending: 1, type: 'dongeng', target: '#home-audio-grid' });
  DK.refreshCredit();
</script>
@endpush
