@extends('layouts.app')

@section('title', 'DAYAKARYA - Platform Creator Economy Indonesia')
@section('desc', 'DAYAKARYA adalah platform Creator Economy Indonesia untuk penulis, storyteller, podcaster, dan kreator digital. Pengguna dapat top up credit untuk membuka konten premium digital tanpa penjualan barang fisik.')
@section('body_class', 'page-home')

@section('content')
<section class="hero hero-premium" aria-labelledby="home-hero-title">
    <div class="container">
        <div class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow">Platform Creator Economy Indonesia</span>
                <h1 id="home-hero-title">DAYAKARYA</h1>
                <p class="hero-lead">Platform Creator Economy Indonesia</p>
                <p>Tempat para penulis, kreator audio, podcaster, dan storyteller mempublikasikan karya digital serta memperoleh penghasilan dari pembaca dan pendengar.</p>
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
                        <strong>Dikelola resmi</strong>
                        <span>Dikelola oleh PT Java Maya Studio bekerja sama dengan Yayasan Pondok Dayacipta Nusantara.</span>
                    </div>
                    <div class="proof-item">
                        <strong>Produk digital penuh</strong>
                        <span>Tidak menjual barang fisik. Semua transaksi dilakukan secara digital di dalam platform.</span>
                    </div>
                </div>
            </div>
            <div class="hero-showcase">
                <div class="hero-card hero-card-primary">
                    <span class="mini-label">Creator Economy</span>
                    <h2>Publikasikan karya digital, buka konten premium, dan dapatkan penghasilan dari audiensmu.</h2>
                    <p>DAYAKARYA membantu karya tampil lebih profesional, lebih mudah dibeli, dan lebih nyaman dinikmati.</p>
                </div>
                <div class="hero-stats">
                    <div class="stat-tile">
                        <span class="label">Top Up Credit</span>
                        <strong>Pembaca melakukan top up credit untuk membuka konten premium digital</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Digital Only</span>
                        <strong>Cerpen, novel, audio story, podcast, buku digital, dan konten edukasi</strong>
                    </div>
                    <div class="stat-tile">
                        <span class="label">Support</span>
                        <strong>Kontak bisnis, alamat usaha, dan jam operasional tampil jelas untuk pengguna</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-label="Trust Elements">
    <div class="container">
        <div class="trust trust-premium">
            <div class="item"><span class="ic">◆</span> Secure payment untuk transaksi digital</div>
            <div class="item"><span class="ic">◆</span> Company identity jelas</div>
            <div class="item"><span class="ic">◆</span> Customer support resmi</div>
            <div class="item"><span class="ic">◆</span> Alamat usaha tampil terbuka</div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="how-it-works-title">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Bagaimana DAYAKARYA Bekerja</span>
                <h2 id="how-it-works-title">Alurnya jelas, baik untuk kreator maupun pembaca.</h2>
            </div>
        </div>
        <div class="how-grid">
            <article class="feature-card">
                <span class="feature-icon">Untuk Kreator</span>
                <h3>Monetisasi karya digital dalam satu alur.</h3>
                <ol class="legal-steps">
                    <li>Upload karya digital</li>
                    <li>Publikasikan untuk pembaca dan pendengar</li>
                    <li>Tentukan konten premium</li>
                    <li>Dapatkan penghasilan dari unlock konten</li>
                </ol>
            </article>
            <article class="feature-card">
                <span class="feature-icon">Untuk Pembaca</span>
                <h3>Akses karya digital premium tanpa alur yang membingungkan.</h3>
                <ol class="legal-steps">
                    <li>Daftar akun pengguna</li>
                    <li>Top Up Credit</li>
                    <li>Unlock konten premium</li>
                    <li>Nikmati karya digital di dalam platform</li>
                </ol>
            </article>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="digital-content-title">
    <div class="container">
        <div class="showcase-panel">
            <div class="section-head section-head-premium">
                <div>
                    <span class="section-kicker">Konten Digital Premium</span>
                    <h2 id="digital-content-title">Jenis produk digital yang tersedia di DAYAKARYA.</h2>
                </div>
            </div>
            <div class="content-grid">
                <article class="content-card">
                    <span class="content-card-kicker">Digital</span>
                    <h3>Cerpen</h3>
                    <p>Karya pendek yang bisa dibaca langsung dan dibuka bagian premium-nya dengan credit.</p>
                </article>
                <article class="content-card">
                    <span class="content-card-kicker">Digital</span>
                    <h3>Novel</h3>
                    <p>Bab per bab dapat dipublikasikan dan dimonetisasi melalui sistem unlock premium.</p>
                </article>
                <article class="content-card">
                    <span class="content-card-kicker">Audio</span>
                    <h3>Audio Story</h3>
                    <p>Cerita audio digital yang nyaman didengar dan dapat dibuka per episode atau bagian premium.</p>
                </article>
                <article class="content-card">
                    <span class="content-card-kicker">Audio</span>
                    <h3>Podcast</h3>
                    <p>Konten audio digital yang bisa dipublikasikan untuk publik maupun akses premium.</p>
                </article>
                <article class="content-card">
                    <span class="content-card-kicker">Digital</span>
                    <h3>Buku Digital</h3>
                    <p>Konten baca digital yang dapat dijual dan dinikmati langsung di dalam platform.</p>
                </article>
                <article class="content-card">
                    <span class="content-card-kicker">Edukasi</span>
                    <h3>Konten Edukasi</h3>
                    <p>Materi belajar digital, seri pengetahuan, dan konten edukatif premium berbasis credit.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="about-dayakarya-title">
    <div class="container">
        <div class="support-identity-panel">
            <div class="support-identity-copy">
                <span class="section-kicker">Tentang DAYAKARYA</span>
                <h2 id="about-dayakarya-title">Platform Creator Economy Indonesia yang dibangun untuk karya digital.</h2>
                <p>DAYAKARYA merupakan platform Creator Economy Indonesia yang dikembangkan oleh PT Java Maya Studio bekerja sama dengan Yayasan Pondok Dayacipta Nusantara.</p>
                <p>Platform ini bertujuan membantu kreator memperoleh penghasilan dari karya digital melalui sistem monetisasi berbasis Credit.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="payment-model-title">
    <div class="container">
        <div class="section-head section-head-premium">
            <div>
                <span class="section-kicker">Cara Pembelian Konten</span>
                <h2 id="payment-model-title">Model transaksi dibuat jelas dan sepenuhnya digital.</h2>
            </div>
        </div>
        <div class="journey-grid">
            <article class="journey-card">
                <span class="journey-step">Langkah 1</span>
                <h3>Pengguna melakukan Top Up Credit</h3>
                <p>Saldo credit digunakan sebagai alat tukar digital di dalam platform untuk membuka konten premium.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 2</span>
                <h3>Credit dipakai untuk unlock konten premium</h3>
                <p>Bab premium, episode premium, audio premium, dan konten premium lainnya dibuka langsung di dalam akun pengguna.</p>
            </article>
            <article class="journey-card">
                <span class="journey-step">Langkah 3</span>
                <h3>Tidak ada penjualan barang fisik</h3>
                <p>Seluruh transaksi dilakukan secara digital di dalam platform DAYAKARYA.</p>
            </article>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="trending-title">
    <div class="container">
        <div class="showcase-panel">
            <div class="section-head">
                <div>
                    <span class="section-kicker">Pilihan Editor</span>
                    <h2 id="trending-title">Sedang Tren</h2>
                </div>
                <a href="{{ route('explore') }}">Lihat semua</a>
            </div>
            <div class="chips" id="category-chips">
                <span class="chip active">Semua</span>
                <span class="chip">Cerpen</span>
                <span class="chip">Novel</span>
                <span class="chip">Podcast</span>
                <span class="chip">Audio Story</span>
                <span class="chip">Buku Digital</span>
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
        <div class="cta-panel">
            <div>
                <span class="section-kicker">Mulai di DAYAKARYA</span>
                <h2>Bangun karya digital, monetisasi audiens, dan kelola pengalaman premium dalam satu platform.</h2>
                <p>DAYAKARYA membantu kreator tampil profesional, pembaca merasa aman, dan alur pembayaran terasa jelas sejak awal.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
                <a href="{{ route('explore') }}" class="btn btn-primary">Jelajahi Karya</a>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="contact-support-title">
    <div class="container">
        <div class="support-identity-panel">
            <div class="support-identity-copy">
                <span class="section-kicker">Kontak Support</span>
                <h2 id="contact-support-title">PT Java Maya Studio siap melayani pengguna dan partner bisnis secara langsung.</h2>
                <p>Informasi bisnis, kontak support, dan alamat usaha ditampilkan terbuka agar pengguna dan tim verifikasi memahami identitas operator DAYAKARYA dengan cepat.</p>
            </div>
            <div class="support-identity-grid">
                <div class="support-identity-item">
                    <strong>PT Java Maya Studio</strong>
                    <span>Creator Economy Platform</span>
                </div>
                <div class="support-identity-item">
                    <strong>Email</strong>
                    <a href="mailto:admin@dayakarya.id">admin@dayakarya.id</a>
                </div>
                <div class="support-identity-item">
                    <strong>Telepon</strong>
                    <a href="tel:085722224391">085722224391</a>
                </div>
                <div class="support-identity-item support-identity-item-full">
                    <strong>Alamat Kantor</strong>
                    <span>Jl. Melati Utama No.18, Cipadung Kidul, Kecamatan Panyileukan, Kota Bandung, Jawa Barat, Indonesia</span>
                </div>
                <div class="support-identity-item support-identity-item-full">
                    <strong>Jam Operasional</strong>
                    <span>Senin - Jumat, 09.00 - 17.00 WIB</span>
                </div>
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
