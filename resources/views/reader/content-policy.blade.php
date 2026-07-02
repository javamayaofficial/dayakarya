@extends('layouts.app')
@section('title', 'Content Policy - DAYAKARYA')
@section('desc', 'Kebijakan konten DAYAKARYA untuk menjaga kualitas karya, kepatuhan hukum, dan keamanan pengguna.')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">Content Policy</span>
            <h1>Kebijakan konten untuk menjaga kualitas karya dan keamanan platform.</h1>
            <p>DAYAKARYA memberi ruang bagi karya digital, namun tetap menerapkan batas yang jelas agar layanan aman, legal, dan layak dipercaya.</p>
        </div>

        <div class="legal-grid">
            <article class="support-panel legal-card">
                <h2 class="support-title">Konten yang diizinkan</h2>
                <p class="support-copy">Kreator dapat mempublikasikan cerpen, novel, audio story, podcast, buku digital, dan konten edukasi yang merupakan karya asli atau memiliki hak penggunaan yang sah.</p>
            </article>

            <article class="support-panel legal-card">
                <h2 class="support-title">Konten yang dilarang</h2>
                <ul class="legal-list">
                    <li>Konten yang melanggar hukum, hak cipta, atau hak privasi pihak lain.</li>
                    <li>Konten penipuan, manipulatif, berbahaya, atau mendorong tindakan ilegal.</li>
                    <li>Konten yang sengaja dibuat untuk menyalahgunakan transaksi atau merugikan pengguna lain.</li>
                </ul>
            </article>

            <article class="support-panel legal-card legal-card-highlight">
                <h2 class="support-title">Moderasi platform</h2>
                <p class="support-copy">DAYAKARYA berhak meninjau, membatasi, menurunkan, atau menonaktifkan akses terhadap konten yang dinilai melanggar kebijakan platform, hukum yang berlaku, atau membahayakan pengalaman pengguna.</p>
            </article>
        </div>
    </div>
</section>
@endsection
