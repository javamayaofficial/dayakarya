@extends('layouts.app')

@section('title', 'Dayakarya — Berkarya, Berdampak, Berpenghasilan')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Creator Economy Indonesia</span>
                <h1>Karya yang serius pantas punya <em>panggung</em> yang setara.</h1>
                <p>Dayakarya menghadirkan terbitan, akses premium, dan monetisasi dalam satu ruang yang rapi.</p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
                    <a href="{{ route('explore') }}" class="btn btn-ghost">Jelajahi Karya</a>
                    <button type="button" class="btn btn-install" data-install-app>
                        <span class="btn-install-ic">↓</span>
                        <span data-install-label>Install App</span>
                    </button>
                </div>
                <p class="install-note" data-install-note>Pasang Dayakarya agar akses terasa cepat dan rapi seperti aplikasi.</p>
                <div class="hero-proof">
                    <div class="proof-item">
                        <strong>Monetisasi yang rapi</strong>
                        <span>Royalti, top up, dan akses premium tersusun jelas.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Ekosistem yang siap tumbuh</strong>
                        <span>Kreator, affiliate, sponsor, dan CSR bertemu dalam satu alur.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase">
                <div class="hero-card hero-card-primary">
                    <span class="mini-label">Creator Economy</span>
                    <h2>Naikkan nilai karya sebelum menaikkan harga aksesnya.</h2>
                    <p>Untuk kreator yang ingin tampil kredibel dan menjual dengan elegan.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-tile">
                        <span class="label">Monetisasi</span>
                        <strong>Royalti, credit, dan affiliate dalam satu sistem</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Distribusi</span>
                        <strong>Siap untuk web dan pengalaman audio-first</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Kolaborasi</span>
                        <strong>Siap dibawa ke sponsor dan CSR</strong>
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
            <div class="item"><span class="ic">◆</span> Didukung Program CSR</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Kenapa Dayakarya</span>
                    <h2>Lebih dari sekadar platform upload</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon">01</span>
                <h3>Karya tampil lebih bernilai</h3>
                <p>Presentasi, metadata, dan alur beli dibuat pantas dijual.</p>
            <article class="feature-card">
                <span class="feature-icon">02</span>
                <h3>Monetisasi terasa meyakinkan</h3>
                <p>Top up, unlock, royalti, dan affiliate tersusun jelas.</p>
            <article class="feature-card">
                <span class="feature-icon">03</span>
                <h3>Siap dibawa ke kolaborasi besar</h3>
                <p>Strukturnya mendukung sponsor dan CSR tanpa kehilangan kredibilitas.</p>
        </div>
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
                {{-- Diisi via JS dari GET /api/v1/works?trending=1 --}}
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
                <span class="section-kicker">Alur Monetisasi</span>
                    <h2>Bangun pendapatan dengan sistem yang rapi.</h2>
            </div>
        </div>
        <div class="journey-grid">
            <article class="journey-card">
                <span class="journey-step">Langkah 1</span>
                <h3>Terbitkan karya dengan positioning yang kuat</h3>
                <p>Bangun katalog yang rapi dan mudah ditemukan.</p>
            <article class="journey-card">
                <span class="journey-step">Langkah 2</span>
                <h3>Aktifkan akses premium yang pantas dibayar</h3>
                <p>Jual pengalaman yang terasa eksklusif dan jelas nilainya.</p>
            <article class="journey-card">
                <span class="journey-step">Langkah 3</span>
                <h3>Perluas distribusi lewat affiliate dan kolaborasi</h3>
                <p>Gunakan affiliate, sponsor, dan CSR untuk memperluas jangkauan.</p>
        </div>
    </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Untuk Kreator yang Ingin Naik Kelas</span>
                <h2>Mulai dari platform yang membuat karya tampak bernilai.</h2>
                <p>Bangun citra, pengalaman, dan monetisasi dalam satu standar yang rapi.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-gold">Mulai Bangun Karyamu</a>
                <a href="{{ route('explore') }}" class="btn btn-primary">Lihat Standar Karyanya</a>
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
