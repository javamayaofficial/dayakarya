@extends('layouts.app')

@section('title', 'Dayakarya - Berkarya, Berdampak, Berpenghasilan')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Dari Skill Jadi Cuan</span>
                <h1>Ubah skill dan hobimu jadi <em>cuan</em>.</h1>
                <p>Di Dayakarya, karya kamu bisa dibaca, dibayar, dan terus berkembang.</p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
                    <a href="{{ route('explore') }}" class="btn btn-ghost">Jelajahi Karya</a>
                    <button type="button" class="btn btn-install" data-install-app>
                        <span class="btn-install-ic">↓</span>
                        <span data-install-label>Install App</span>
                    </button>
                </div>
                <p class="install-note" data-install-note>Pasang Dayakarya biar bukanya lebih cepat dan nyaman, seperti aplikasi.</p>
                <div class="hero-proof">
                    <div class="proof-item">
                        <strong>Mulai cuan tanpa ribet</strong>
                        <span>Akses berbayar, top up, dan royalti sudah siap dalam satu alur.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Skill bisa terus berkembang</strong>
                        <span>Karya, pembaca, dan peluang share bisa tumbuh di tempat yang sama.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase">
                <div class="hero-card hero-card-primary">
                    <span class="mini-label">Untuk Kreator</span>
                    <h2>Yang kamu bisa dan yang kamu suka, sekarang bisa jadi penghasilan juga.</h2>
                    <p>Taruh di tempat yang bikin orang lebih gampang lihat, percaya, dan beli.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-tile">
                        <span class="label">Penghasilan</span>
                        <strong>Top up, royalti, dan affiliate jalan di satu tempat</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Sebar</span>
                        <strong>Bisa dibuka di web dan tetap nyaman buat konten audio</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Promosi</span>
                        <strong>Lebih gampang dibagikan ke pembaca baru, komunitas, dan partner promosi</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="trust trust-premium">
            <div class="item"><span class="ic">◆</span> Royalti otomatis</div>
            <div class="item"><span class="ic">◆</span> Withdraw ke rekening</div>
            <div class="item"><span class="ic">◆</span> Komisi affiliate</div>
            <div class="item"><span class="ic">◆</span> Bisa bantu share karya</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Kenapa Dayakarya</span>
                <h2>Bukan cuma tempat upload karya</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon">01</span>
                <h3>Karya terlihat lebih rapi</h3>
                <p>Tampilan, info, dan alur belinya dibuat lebih enak dilihat.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">02</span>
                <h3>Jual karya jadi lebih jelas</h3>
                <p>Top up, unlock, royalti, dan affiliate sudah disusun biar tidak bikin bingung.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">03</span>
                <h3>Lebih enak dibagikan</h3>
                <p>Kalau mau dipromosikan atau dibantu sebar, tampilannya sudah lebih siap.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="showcase-panel">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Pilihan Editor</span>
                    <h2>Sedang Tren</h2>
                </div>
                <a href="{{ route('explore') }}">Lihat semua</a>
            </div>
            <div class="chips" id="category-chips">
                <span class="chip active">Semua</span>
                <span class="chip">Cerpen</span>
                <span class="chip">Novel</span>
                <span class="chip">Podcast</span>
                <span class="chip">Dongeng</span>
                <span class="chip">Audiobook</span>
            </div>
            <div style="height:14px"></div>
            <div class="work-grid" id="trending-grid">
                <div class="state" style="grid-column:1/-1">
                    <div class="emoji">📚</div>
                    <p>Memuat karya pilihan…</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Cara Mulainya</span>
                <h2>Mulai dapat penghasilan dari karya, pelan-pelan tapi jelas.</h2>
            </div>
        </div>
        <div class="journey-grid">
            <article class="journey-card">
                <span class="journey-step">Langkah 1</span>
                <h3>Upload karya dan rapikan tampilannya</h3>
                <p>Bikin orang lebih gampang nemu dan tertarik baca karya kamu.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 2</span>
                <h3>Buka akses berbayar kalau sudah siap</h3>
                <p>Jual karya atau bagian premium tanpa alur yang ribet.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 3</span>
                <h3>Besarkan jangkauan pelan-pelan</h3>
                <p>Pakai affiliate atau kerja sama biar karya kamu makin banyak yang lihat.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Kalau Mau Mulai Serius</span>
                <h2>Mulai dari tempat yang bikin karya kamu terasa lebih layak dilihat dan dibayar.</h2>
                <p>Biar karya, pengalaman pembaca, dan penghasilan kamu tumbuh di tempat yang sama.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-gold">Mulai dari Sekarang</a>
                <a href="{{ route('explore') }}" class="btn btn-primary">Lihat Contohnya</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  // Muat karya trending dari REST API — struktur sama dipakai aplikasi mobile
  DK.loadWorks({ trending: 1, target: '#trending-grid' });
  DK.refreshCredit();
</script>
@endpush
