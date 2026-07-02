@extends('layouts.app')
@section('title', 'Refund Policy - DAYAKARYA')
@section('desc', 'Kebijakan refund DAYAKARYA untuk transaksi top up credit dan akses konten digital premium.')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">Refund Policy</span>
            <h1>Kebijakan refund untuk transaksi digital di DAYAKARYA.</h1>
            <p>Halaman ini menjelaskan bagaimana DAYAKARYA menangani top up credit, transaksi gagal, dan permintaan refund yang relevan dengan layanan digital.</p>
        </div>

        <div class="legal-grid">
            <article class="support-panel legal-card">
                <h2 class="support-title">Ruang lingkup</h2>
                <p class="support-copy">DAYAKARYA hanya melayani produk digital. Refund hanya dipertimbangkan untuk kondisi yang memiliki dasar operasional yang jelas, seperti top up berhasil dibayar tetapi saldo tidak masuk karena gangguan sistem.</p>
            </article>

            <article class="support-panel legal-card">
                <h2 class="support-title">Top up credit</h2>
                <ul class="legal-list">
                    <li>Top up credit yang sudah berhasil masuk ke akun umumnya tidak dapat dikembalikan menjadi uang tunai.</li>
                    <li>Jika terjadi duplikasi pembayaran, kegagalan sistem, atau kesalahan pencatatan, pengguna dapat menghubungi support untuk verifikasi.</li>
                </ul>
            </article>

            <article class="support-panel legal-card">
                <h2 class="support-title">Akses konten premium</h2>
                <p class="support-copy">Credit yang telah digunakan untuk membuka bab premium, episode premium, audio premium, atau konten premium lain dianggap sebagai transaksi digital yang telah dipakai sesuai fungsi layanan.</p>
            </article>

            <article class="support-panel legal-card legal-card-highlight">
                <h2 class="support-title">Kontak pengajuan</h2>
                <p class="support-copy">Untuk permintaan verifikasi transaksi atau kendala credit, hubungi <a href="mailto:admin@dayakarya.id">admin@dayakarya.id</a> atau <a href="tel:085722224391">085722224391</a> dengan menyertakan email akun, order ID, dan ringkasan kendala.</p>
            </article>
        </div>
    </div>
</section>
@endsection
