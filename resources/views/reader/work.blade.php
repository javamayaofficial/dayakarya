@extends('layouts.app')
@section('title', $work->title . ' — Dayakarya')
@section('desc', Str::limit($work->synopsis, 150))

@section('content')
<section class="section">
    <div class="container">
        <div class="card" style="display:flex;gap:16px;align-items:flex-start">
            <div class="work-cover" style="width:110px;flex:0 0 110px;border-radius:14px;{{ $work->cover ? "background-image:url('".$work->cover."');background-size:cover" : '' }}">
                <span class="type-tag">{{ config('dayakarya.work_types')[$work->type] ?? $work->type }}</span>
            </div>
            <div>
                <h1 style="font-size:1.4rem;line-height:1.2">{{ $work->title }}</h1>
                <div class="work-meta" style="margin:8px 0">✍️ {{ $work->creator->name }} · {{ number_format($work->views) }}x dibaca</div>
                <button class="btn btn-ghost" style="padding:8px 16px;min-height:40px" onclick="DK.follow({{ $work->creator_id }})">+ Ikuti Kreator</button>
            </div>
        </div>

        <div class="section" style="padding:20px 0 8px">
            <p style="color:var(--ink-soft)">{{ $work->synopsis }}</p>
        </div>

        <div class="section-head"><h2>Daftar Bagian</h2></div>
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
