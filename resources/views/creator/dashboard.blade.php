@extends('layouts.app')
@section('title', 'Dashboard Kreator — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Creator Cockpit</span>
                <h1>Bangun katalog yang lebih bernilai, baca angkanya dengan tenang, dan arahkan monetisasi dengan lebih percaya diri.</h1>
                <p>Dashboard kreator Dayakarya dirancang untuk memberi kontrol, kejelasan, dan ruang kerja yang terasa lebih profesional saat Anda membangun karya digital yang pantas dibayar.</p>
                <div class="creator-hero-actions">
                    <a href="#" class="btn btn-gold">＋ Karya Baru</a>
                    <a href="{{ route('wallet') }}" class="btn btn-ghost">Tarik Penghasilan</a>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Monetization Ready</span>
                <h2>Creator dashboard bukan sekadar statistik, tetapi meja kendali untuk karya yang ingin tumbuh serius.</h2>
                <p>Dari jumlah karya, pembacaan, royalti, hingga pengikut, semua disusun agar kreator bisa mengambil keputusan yang lebih tajam dan lebih bernilai.</p>
            </div>
        </div>

        <div class="creator-stat-grid">
            <div class="stat stat-feature">
                <div class="label">Total Karya</div>
                <div class="value" id="s-works">—</div>
                <p>Jumlah karya aktif yang sedang membentuk katalog, reputasi, dan posisi Anda di Dayakarya.</p>
            </div>
            <div class="stat gold stat-feature">
                <div class="label">Total Dibaca</div>
                <div class="value" id="s-views">—</div>
                <p>Indikator awal seberapa kuat distribusi dan daya tarik karya Anda di mata audiens.</p>
            </div>
            <div class="stat teal stat-feature">
                <div class="label">Royalti (Rupiah)</div>
                <div class="value" id="s-royalty">—</div>
                <p>Ringkasan nilai ekonomi yang sudah berhasil Anda tarik dari katalog yang dibangun.</p>
            </div>
            <div class="stat stat-feature">
                <div class="label">Pengikut</div>
                <div class="value" id="s-followers">—</div>
                <p>Basis audiens yang berpotensi menjadi pembaca loyal, pendengar setia, dan pendukung jangka panjang.</p>
            </div>
        </div>

        <div class="creator-panel-grid">
            <div class="creator-panel card">
                <div class="section-head section-head-premium">
                    <div>
                        <span class="section-kicker">Katalog Kreator</span>
                        <h2>Karyaku</h2>
                    </div>
                </div>
                <div class="work-grid work-grid-premium" id="my-works">
                    <div class="state" style="grid-column:1/-1">
                        <div class="emoji">🖋️</div>
                        <h3>Belum ada karya</h3>
                        <p>Mulai terbitkan cerpen, novel, atau podcast pertama Anda dengan standar presentasi yang lebih layak dijual.</p>
                        <a href="#" class="btn btn-gold">Buat Karya Pertama</a>
                    </div>
                </div>
            </div>

            <aside class="creator-side">
                <div class="creator-side-card">
                    <span class="section-kicker">Prioritas Berikutnya</span>
                    <h3>Naikkan nilai katalog, bukan sekadar menambah stok konten.</h3>
                    <p>Fokus pada konsistensi terbit, kualitas presentasi, dan alur premium untuk membangun kepercayaan sekaligus daya beli audiens.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft">
                    <span class="section-kicker">Monetisasi</span>
                    <h3>Royalti, wallet, dan affiliate bekerja paling baik saat katalog Anda terasa bernilai.</h3>
                    <p>Gunakan dashboard ini sebagai pusat keputusan: karya mana yang perlu dipromosikan, dipremiumkan, atau diperkuat distribusinya.</p>
                </div>
            </aside>
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
