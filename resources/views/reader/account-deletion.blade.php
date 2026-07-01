@extends('layouts.app')
@section('title', 'Penghapusan Akun — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-hero">
            <span class="section-kicker">Penghapusan Akun</span>
            <h1>Permintaan penghapusan akun tersedia melalui prosedur resmi yang jelas dan dapat dilacak.</h1>
            <p>Dayakarya saat ini memproses penghapusan akun melalui permintaan manual agar identitas dan histori transaksi dapat diverifikasi dengan lebih aman sebelum data dinonaktifkan atau dihapus sesuai kebutuhan operasional.</p>
        </div>

        <div class="support-panel legal-card">
            <h2 class="support-title">Cara meminta penghapusan akun</h2>
            <ol class="legal-steps">
                <li>Kirim email ke <a href="mailto:admin@dayakarya.id?subject=Permintaan%20Penghapusan%20Akun%20Dayakarya">admin@dayakarya.id</a> dengan subjek <strong>Permintaan Penghapusan Akun Dayakarya</strong>.</li>
                <li>Sertakan nama akun, email yang terdaftar, dan alasan singkat permintaan agar proses verifikasi berjalan lebih cepat.</li>
                <li>Tim Dayakarya dapat meminta verifikasi tambahan untuk memastikan permintaan benar-benar berasal dari pemilik akun.</li>
            </ol>
        </div>

        <div class="support-panel legal-card">
            <h2 class="support-title">Data yang akan diproses</h2>
            <ul class="legal-list">
                <li>Data profil akun dan akses login akan dinonaktifkan setelah permintaan disetujui.</li>
                <li>Data tertentu yang masih wajib disimpan untuk kebutuhan hukum, audit, keamanan, atau rekonsiliasi transaksi dapat dipertahankan sesuai kebutuhan yang berlaku.</li>
                <li>Konten atau histori tertentu yang terikat pada transaksi atau catatan operasional dapat diproses sesuai kebijakan internal platform.</li>
            </ul>
        </div>

        <div class="support-panel legal-card legal-card-highlight">
            <h2 class="support-title">Estimasi penanganan</h2>
            <p class="support-copy">Permintaan penghapusan akun ditinjau secara manual setelah verifikasi diterima. Tim Dayakarya akan mengupayakan respons awal dalam waktu yang wajar pada hari kerja.</p>
            <p class="legal-note">Halaman ini disediakan agar pengguna dan reviewer platform memiliki jalur penghapusan akun yang mudah ditemukan, jelas, dan terdokumentasi.</p>
        </div>
    </div>
</section>
@endsection
