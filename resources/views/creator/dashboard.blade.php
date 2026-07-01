@extends('layouts.app')
@section('title', 'Dashboard Kreator — Dayakarya')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head"><h2>Dashboard Kreator</h2></div>

        <div class="stat-grid">
            <div class="stat"><div class="label">Total Karya</div><div class="value" id="s-works">—</div></div>
            <div class="stat gold"><div class="label">Total Dibaca</div><div class="value" id="s-views">—</div></div>
            <div class="stat teal"><div class="label">Royalti (Rupiah)</div><div class="value" id="s-royalty">—</div></div>
            <div class="stat"><div class="label">Pengikut</div><div class="value" id="s-followers">—</div></div>
        </div>

        <div style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">
            <a href="#" class="btn btn-gold">＋ Karya Baru</a>
            <a href="{{ route('wallet') }}" class="btn btn-ghost">Tarik Penghasilan</a>
        </div>

        <div class="section-head"><h2>Karyaku</h2></div>
        <div class="work-grid" id="my-works">
            <div class="state" style="grid-column:1/-1">
                <div class="emoji">🖋️</div>
                <h3>Belum ada karya</h3>
                <p>Mulai tulis cerpen, novel, atau rekam podcast pertamamu.</p>
                <a href="#" class="btn btn-gold">Buat Karya Pertama</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  if (!DK.token()) location.href = '/masuk';
  // Data diisi dari endpoint creator (mis. GET /api/v1/creator/stats saat diimplementasikan)
</script>
@endpush
