@extends('layouts.app')

@section('title', 'Dayakarya — Berkarya, Berdampak, Berpenghasilan')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Ekosistem Kreator Indonesia</span>
                <h1>Bangun karya yang <em>terasa bernilai</em>, menghasilkan, dan tumbuh jadi dampak nyata.</h1>
                <p>Dayakarya membantu penulis, pendongeng, podcaster, dan kreator audio membangun karya digital yang lebih rapi, lebih bernilai, dan lebih mudah dimonetisasi.</p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
                    <a href="{{ route('explore') }}" class="btn btn-ghost">Jelajahi Karya</a>
                </div>
                <div class="hero-proof">
                    <div class="proof-item">
                        <strong>Royalti otomatis</strong>
                        <span>Pendapatan tercatat rapi untuk tiap karya yang dinikmati.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Ekosistem berlapis</strong>
                        <span>Kreator, affiliate, sponsor, dan CSR ada dalam satu alur.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase">
                <div class="hero-card hero-card-primary">
                    <span class="mini-label">Creator Economy</span>
                    <h2>Ubah cerita, audio, dan komunitas menjadi aset digital yang kredibel.</h2>
                    <p>Dirancang untuk kreator yang ingin bertumbuh dengan citra premium, alur monetisasi jelas, dan pengalaman pengguna yang lebih meyakinkan.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-tile">
                        <span class="label">Monetisasi</span>
                        <strong>Royalti, top up, affiliate</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Distribusi</span>
                        <strong>Web, mobile web, audio-first</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Kolaborasi</span>
                        <strong>CSR dan sponsor siap terhubung</strong>
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
                <h2>Pengalaman yang lebih serius untuk kreator yang ingin naik kelas</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon">01</span>
                <h3>Brand karya terasa lebih rapi</h3>
                <p>Tampilan karya, metadata, dan perjalanan pengguna dibangun agar terasa lebih profesional dan pantas dijual.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">02</span>
                <h3>Monetisasi tidak terasa acak</h3>
                <p>Top up, unlock premium, royalti, dan affiliate disusun dalam alur yang lebih jelas dan mudah dipahami.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">03</span>
                <h3>Siap dibawa ke program dampak</h3>
                <p>Strukturnya mendukung kolaborasi dengan sponsor dan program CSR tanpa terasa seperti platform biasa.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="showcase-panel">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Pilihan Untuk Dinikmati</span>
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
                <h2>Bukan sekadar unggah karya, tapi bangun mesin pendapatan yang rapi</h2>
            </div>
        </div>
        <div class="journey-grid">
            <article class="journey-card">
                <span class="journey-step">Langkah 1</span>
                <h3>Terbitkan karya dengan positioning yang tepat</h3>
                <p>Bangun katalog yang terasa tertata, mudah ditemukan, dan cukup kuat untuk membangun kepercayaan audiens.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 2</span>
                <h3>Aktifkan akses premium dan credit</h3>
                <p>Biarkan pembaca atau pendengar membeli pengalaman, bukan sekadar membuka halaman atau audio.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 3</span>
                <h3>Perluas distribusi lewat affiliate dan program dampak</h3>
                <p>Gunakan komisi affiliate dan dukungan CSR agar pertumbuhan karya tidak hanya bergantung pada promosi tunggal.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Untuk Kreator Serius</span>
                <h2>Kalau Anda ingin karya terasa lebih premium, Dayakarya siap jadi rumah tumbuhnya.</h2>
                <p>Mulai dari katalog yang lebih rapi, pengalaman pengguna yang lebih meyakinkan, sampai monetisasi yang lebih terstruktur.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-gold">Buat Akun Kreator</a>
                <a href="{{ route('explore') }}" class="btn btn-primary">Lihat Karya Tersedia</a>
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
