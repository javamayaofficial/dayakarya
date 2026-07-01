@extends('layouts.app')

@section('title', 'Dayakarya — Berkarya, Berdampak, Berpenghasilan')

@section('content')
<section class="hero">
    <div class="container">
        <span class="eyebrow">Rumah Kreator Indonesia</span>
        <h1>Ubah karyamu jadi <em>penghasilan</em>. Ubah dukungan jadi <em>dampak</em>.</h1>
        <p>Tulis cerita, rekam podcast, bagikan dongeng — dan dapatkan royalti otomatis setiap karyamu dinikmati.</p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn btn-gold">Mulai Berkarya</a>
            <a href="{{ route('explore') }}" class="btn btn-ghost">Jelajahi Karya</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="trust">
            <div class="item"><span class="ic">◆</span> Royalti otomatis</div>
            <div class="item"><span class="ic">◆</span> Withdraw ke rekening</div>
            <div class="item"><span class="ic">◆</span> Komisi affiliate</div>
            <div class="item"><span class="ic">◆</span> Didukung Program CSR</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Sedang Tren</h2>
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
</section>
@endsection

@push('scripts')
<script>
  // Muat karya trending dari REST API — struktur sama dipakai aplikasi mobile
  DK.loadWorks({ trending: 1, target: '#trending-grid' });
  DK.refreshCredit();
</script>
@endpush
