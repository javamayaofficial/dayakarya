@extends('layouts.app')
@section('title', $work->title . ' — Dayakarya')
@section('desc', Str::limit($work->synopsis, 150))
@section('body_class', 'page-work')

@section('content')
<section class="section">
    <div class="container">
        <div class="work-hero card">
            <div class="work-hero-cover work-cover" style="{{ $work->cover ? "background-image:url('".$work->cover."');background-size:cover" : '' }}">
                <span class="type-tag">{{ config('dayakarya.work_types')[$work->type] ?? $work->type }}</span>
            </div>
            <div class="work-hero-copy">
                <span class="section-kicker">Karya Unggulan</span>
                <h1>{{ $work->title }}</h1>
                <div class="work-meta work-meta-rich">
                    <span>✍️ {{ $work->creator->name }}</span>
                    <span>•</span>
                    <span>{{ number_format($work->views) }}x dibaca</span>
                    <span>•</span>
                    <span>{{ $work->chapters->where('status','published')->count() }} bagian</span>
                </div>
                <p class="work-synopsis">{{ $work->synopsis }}</p>
                <div class="work-hero-actions">
                    <button class="btn btn-ghost" onclick="DK.follow({{ $work->creator_id }})">+ Ikuti Kreator</button>
                    <a href="#daftar-bagian" class="btn btn-primary">Mulai Baca</a>
                </div>
                <div class="work-badges">
                    <span class="work-badge">Layak masuk katalog premium</span>
                    <span class="work-badge">Disusun untuk audiens yang serius</span>
                </div>
            </div>
        </div>

        <div class="section work-context">
            <div class="work-context-grid">
                <div class="context-card">
                    <span class="mini-label mini-label-dark">Tentang karya ini</span>
                    <h2>Karya ini dipresentasikan dengan rasa nilai.</h2>
                    <p>Di Dayakarya, karya diposisikan sebagai aset digital yang layak diapresiasi.</p>
                </div>
                <div class="context-card context-card-soft">
                    <span class="mini-label mini-label-dark">Akses konten</span>
                    <h3>Bagian gratis membuka rasa penasaran. Bagian premium menjual pengalaman penuh.</h3>
                    <p>Alurnya dibuat nyaman sejak awal hingga akses premium.</p>
                </div>
            </div>
        </div>

        <div class="section-head section-head-premium" id="daftar-bagian">
            <div>
                <span class="section-kicker">Daftar Bagian</span>
                <h2>Pilih bagian yang ingin Anda nikmati</h2>
            </div>
        </div>
        @foreach($work->chapters->where('status','published') as $ch)
            <div class="chapter-row">
                <div style="display:flex;align-items:center;gap:12px">
                    <span class="idx">{{ sprintf('%02d', $ch->order) }}</span>
                    <div>
                        <div style="font-weight:600">{{ $ch->title }}</div>
                        @if($ch->is_premium)
                            <span class="lock">🔒 {{ $ch->price_credit }} Credit</span>
                        @else
                            <span class="free">Gratis</span>
                        @endif
                    </div>
                </div>
                @if($ch->is_premium)
                    <button class="btn btn-gold" style="padding:9px 16px;min-height:40px" onclick="DK.unlock({{ $ch->id }})">Buka</button>
                @else
                    <a class="btn btn-ghost" style="padding:9px 16px;min-height:40px" href="#baca-{{ $ch->id }}">Baca</a>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts')
<script>
  DK.refreshCredit();
  DK.unlock = async function(chapterId) {
    if (!DK.token()) { location.href = '/masuk'; return; }
    const ref = (document.cookie.match(/dk_ref=([^;]+)/) || [])[1];
    const { ok, data } = await DK.post('/chapters/' + chapterId + '/unlock', { ref });
    alert(data.message || (ok ? 'Berhasil dibuka!' : 'Gagal.'));
    if (ok) { DK.refreshCredit(); location.reload(); }
  };
  DK.follow = async function(creatorId) {
    if (!DK.token()) { location.href = '/masuk'; return; }
    alert('Kamu sekarang mengikuti kreator ini.');
  };
</script>
@endpush
