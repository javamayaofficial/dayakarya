@extends('layouts.app')

@section('title', 'Dayakarya — Berkarya, Berdampak, Berpenghasilan')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Creator Economy Indonesia</span>
                <h1>Karya yang serius pantas punya <em>panggung</em>, audiens, dan pendapatan yang setara.</h1>
                <p>Dayakarya membantu penulis, pendongeng, podcaster, dan kreator audio menerbitkan karya dengan citra yang lebih berkelas, akses premium yang rapi, dan monetisasi yang terasa profesional.</p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
                    <a href="{{ route('explore') }}" class="btn btn-ghost">Jelajahi Karya</a>
                </div>
                <div class="hero-proof">
                    <div class="proof-item">
                        <strong>Monetisasi yang tertata</strong>
                        <span>Royalti, top up, dan akses premium disusun agar pendapatan terasa jelas dan terpercaya.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Ekosistem yang siap tumbuh</strong>
                        <span>Kreator, affiliate, sponsor, dan CSR bertemu dalam satu alur yang lebih bernilai.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase">
                <div class="hero-card hero-card-primary">
                    <span class="mini-label">Creator Economy</span>
                    <h2>Naikkan nilai karya Anda sebelum menaikkan harga aksesnya.</h2>
                    <p>Dirancang untuk kreator yang ingin tampil lebih kredibel, menjual dengan lebih elegan, dan membangun pengalaman pengguna yang terasa premium sejak pertama dilihat.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-tile">
                        <span class="label">Monetisasi</span>
                        <strong>Royalti, credit, dan affiliate dalam satu sistem</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Distribusi</span>
                        <strong>Siap untuk web, mobile web, dan pengalaman audio-first</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Kolaborasi</span>
                        <strong>Mudah dibawa ke program sponsor dan CSR</strong>
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
                    <h2>Alasan Dayakarya terasa lebih layak dipilih daripada sekadar platform upload biasa</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon">01</span>
                <h3>Brand karya terasa lebih rapi</h3>
                <h3>Karya Anda tampil lebih bernilai</h3>
                <p>Presentasi karya, metadata, dan alur pembelian dibangun agar terasa pantas dijual, bukan sekadar dipajang.</p>
            <article class="feature-card">
                <span class="feature-icon">02</span>
                <h3>Monetisasi tidak terasa acak</h3>
                <h3>Monetisasi terasa lebih meyakinkan</h3>
                <p>Top up, unlock premium, royalti, dan affiliate disusun agar pengguna percaya, paham, dan nyaman bertransaksi.</p>
            <article class="feature-card">
                <span class="feature-icon">03</span>
                <h3>Siap dibawa ke program dampak</h3>
                <h3>Layak dibawa ke kolaborasi yang lebih besar</h3>
                <p>Strukturnya mendukung sponsor dan program CSR, sehingga karya Anda punya peluang tumbuh menjadi dampak yang nyata.</p>
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
                    <h2>Bukan sekadar unggah karya. Bangun sistem pendapatan yang terasa lebih profesional.</h2>
            </div>
        </div>
        <div class="journey-grid">
            <article class="journey-card">
                <span class="journey-step">Langkah 1</span>
                <h3>Terbitkan karya dengan positioning yang tepat</h3>
                <h3>Terbitkan karya dengan positioning yang lebih kuat</h3>
                <p>Bangun katalog yang rapi, mudah ditemukan, dan cukup meyakinkan untuk membuat audiens ingin masuk lebih jauh.</p>
            <article class="journey-card">
                <span class="journey-step">Langkah 2</span>
                <h3>Aktifkan akses premium dan credit</h3>
                <h3>Aktifkan akses premium yang terasa pantas dibayar</h3>
                <p>Biarkan pembaca dan pendengar membeli pengalaman yang lebih eksklusif, bukan sekadar membuka halaman atau audio.</p>
            <article class="journey-card">
                <span class="journey-step">Langkah 3</span>
                <h3>Perluas distribusi lewat affiliate dan program dampak</h3>
                <h3>Perluas distribusi lewat affiliate dan kolaborasi dampak</h3>
                <p>Gunakan affiliate, sponsor, dan CSR agar pertumbuhan karya tidak berhenti di promosi, tetapi berkembang menjadi pengaruh.</p>
        </div>
    </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Untuk Kreator yang Ingin Naik Kelas</span>
                <h2>Jika karya Anda pantas dihargai lebih tinggi, mulailah dengan platform yang membuatnya tampak bernilai.</h2>
                <p>Dayakarya membantu Anda membangun citra, pengalaman, dan monetisasi yang lebih rapi sehingga karya tidak hanya dinikmati, tetapi juga layak dibayar.</p>
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
