@extends('layouts.app')
@section('title', 'Jelajah Karya — Dayakarya')
@section('body_class', 'page-explore')

@section('content')
<section class="section explore-hero">
    <div class="container">
        <div class="explore-shell">
            <div class="explore-copy">
                <span class="section-kicker">Katalog Dayakarya</span>
                <h1>Jelajahi karya yang terasa lebih bernilai, lebih rapi, dan lebih layak dinikmati.</h1>
                <p>Temukan cerita, novel, podcast, dongeng, dan audiobook dalam pengalaman eksplorasi yang lebih tenang, lebih premium, dan lebih mudah dipercaya.</p>
            </div>
            <div class="explore-highlight">
                <div class="highlight-card">
                    <span class="mini-label">Kurasi Dayakarya</span>
                    <h2>Katalog disusun untuk memberi kesan kualitas, bukan sekadar daftar konten.</h2>
                    <p>Gunakan pencarian, filter kategori, dan susunan karya untuk menemukan yang paling sesuai dengan selera Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="explore-toolbar">
            <div class="section-head section-head-premium">
                <div>
                    <span class="section-kicker">Temukan Karya</span>
                    <h2>Jelajah premium untuk pembaca dan pendengar</h2>
                </div>
            </div>
            <div class="search-shell">
                <label class="search-label" for="search">Cari karya</label>
                <div class="search-input-wrap">
                    <span class="search-ic">⌕</span>
                    <input type="search" id="search" placeholder="Cari cerpen, novel, podcast, dongeng…">
                </div>
            </div>
            <div class="chips chips-elevated" id="type-chips">
                <span class="chip active" data-type="">Semua</span>
                <span class="chip" data-type="cerpen">Cerpen</span>
                <span class="chip" data-type="novel">Novel</span>
                <span class="chip" data-type="podcast">Podcast</span>
                <span class="chip" data-type="audio_story">Audio Story</span>
                <span class="chip" data-type="dongeng">Dongeng</span>
                <span class="chip" data-type="audiobook">Audiobook</span>
            </div>
        </div>

        <div class="explore-meta">
            <div class="meta-card">
                <strong>Eksplorasi lebih terarah</strong>
                <span>Filter kategori dan pencarian dibuat untuk membantu Anda menemukan karya yang paling relevan.</span>
            </div>
            <div class="meta-card">
                <strong>Kualitas presentasi lebih baik</strong>
                <span>Setiap karya ditampilkan dengan gaya yang lebih editorial agar terasa lebih bernilai.</span>
            </div>
            <div class="meta-card">
                <strong>Siap untuk premium content</strong>
                <span>Struktur katalog mendukung pengalaman gratis maupun premium tanpa terasa kasar.</span>
            </div>
        </div>

        <div style="height:18px"></div>
        <div class="work-grid work-grid-premium" id="explore-grid"></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  let currentType = '';
  DK.loadWorks({ type: '', target: '#explore-grid' });

  document.querySelectorAll('#type-chips .chip').forEach(chip => {
    chip.addEventListener('click', () => {
      document.querySelectorAll('#type-chips .chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      currentType = chip.dataset.type;
      DK.loadWorks({ type: currentType, target: '#explore-grid' });
    });
  });

  let t;
  document.querySelector('#search').addEventListener('input', (e) => {
    clearTimeout(t);
    t = setTimeout(async () => {
      const el = document.querySelector('#explore-grid');
      const json = await DK.get('/works?search=' + encodeURIComponent(e.target.value));
      const items = json.data ?? [];
      el.innerHTML = items.length ? items.map(w => DK.workCard(w)).join('')
        : `<div class="state" style="grid-column:1/-1"><div class="emoji">🔍</div><h3>Tidak ditemukan</h3><p>Coba kata kunci lain.</p></div>`;
    }, 350);
  });
</script>
@endpush
