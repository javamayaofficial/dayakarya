@extends('layouts.app')
@section('title', 'Jelajah Karya — Dayakarya')
@section('body_class', 'page-explore')

@section('content')
<section class="section explore-hero">
    <div class="container">
        <div class="explore-shell">
            <div class="explore-copy">
                <span class="section-kicker">Jelajahi Karya</span>
                <h1>Cari karya yang enak dibaca, didengar, ditonton, dan layak kamu ikuti.</h1>
                <p>Cerita, podcast, video series, dongeng, dan audiobook dikumpulkan di katalog yang rapi.</p>
            </div>
            <div class="explore-highlight">
                <div class="highlight-card">
                    <span class="mini-label">Pilihan Editor</span>
                    <h2>Di sini karya tidak numpuk begitu saja. Semuanya tampil lebih enak dilihat.</h2>
                    <p>Pakai pencarian dan filter biar kamu cepat ketemu yang cocok.</p>
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
                    <h2>Lagi cari bacaan, audio, atau video yang pas?</h2>
                </div>
            </div>
            <div class="search-shell">
                <label class="search-label" for="search">Cari karya</label>
                <div class="search-input-wrap">
                    <span class="search-ic">⌕</span>
                    <input type="search" id="search" placeholder="Cari cerpen, novel, podcast, video series…">
                </div>
            </div>
            <div class="chips chips-elevated" id="type-chips">
                <span class="chip active" data-type="">Semua</span>
                <span class="chip" data-type="cerpen">Cerpen</span>
                <span class="chip" data-type="novel">Novel</span>
                <span class="chip" data-type="podcast">Podcast</span>
                <span class="chip" data-type="audio_story">Audio Story</span>
                <span class="chip" data-type="video_series">Video Series</span>
                <span class="chip" data-type="dongeng">Dongeng</span>
                <span class="chip" data-type="audiobook">Audiobook</span>
            </div>
        </div>

        <div class="explore-meta">
            <div class="meta-card">
                <strong>Cari lebih cepat</strong>
                <span>Filter dan pencarian bantu kamu nemu yang pas.</span>
            </div>
            <div class="meta-card">
                <strong>Tampil lebih enak</strong>
                <span>Setiap karya ditata biar lebih gampang dinikmati.</span>
            </div>
            <div class="meta-card">
                <strong>Siap untuk konten premium</strong>
                <span>Gratis atau berbayar, alurnya tetap terasa rapi.</span>
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
        : `<div class="state" style="grid-column:1/-1"><div class="emoji">🔍</div><h3>Belum ketemu</h3><p>Coba ganti kata kunci atau pilih kategori lain.</p></div>`;
    }, 350);
  });
</script>
@endpush
