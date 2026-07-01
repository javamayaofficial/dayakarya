@extends('layouts.app')
@section('title', 'Jelajah Karya — Dayakarya')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head"><h2>Jelajah</h2></div>
        <div class="field">
            <input type="search" id="search" placeholder="Cari cerpen, novel, podcast, dongeng…">
        </div>
        <div class="chips" id="type-chips">
            <span class="chip active" data-type="">Semua</span>
            <span class="chip" data-type="cerpen">Cerpen</span>
            <span class="chip" data-type="novel">Novel</span>
            <span class="chip" data-type="podcast">Podcast</span>
            <span class="chip" data-type="audio_story">Audio Story</span>
            <span class="chip" data-type="dongeng">Dongeng</span>
            <span class="chip" data-type="audiobook">Audiobook</span>
        </div>
        <div style="height:16px"></div>
        <div class="work-grid" id="explore-grid"></div>
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
