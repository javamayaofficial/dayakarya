@extends('layouts.app')
@section('title', 'Creator Agreement - DAYAKARYA')
@section('desc', 'Kesepakatan kreator DAYAKARYA terkait publikasi karya digital, monetisasi, dan tanggung jawab konten.')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">Creator Agreement</span>
            <h1>Kesepakatan dasar bagi kreator yang mempublikasikan karya di DAYAKARYA.</h1>
            <p>Halaman ini menjelaskan tanggung jawab kreator, monetisasi, dan batas penggunaan layanan bagi penulis, podcaster, storyteller, dan kreator digital lainnya.</p>
        </div>

        <div class="legal-grid">
            <article class="support-panel legal-card">
                <h2 class="support-title">Hak atas karya</h2>
                <p class="support-copy">Kreator menyatakan bahwa karya yang diunggah merupakan karya miliknya atau sudah memiliki izin penggunaan yang sah untuk dipublikasikan di DAYAKARYA.</p>
            </article>

            <article class="support-panel legal-card">
                <h2 class="support-title">Monetisasi</h2>
                <p class="support-copy">Kreator dapat menentukan konten premium dan memperoleh penghasilan sesuai sistem credit, kebijakan platform, dan ketentuan operasional yang berlaku.</p>
            </article>

            <article class="support-panel legal-card">
                <h2 class="support-title">Tanggung jawab kreator</h2>
                <ul class="legal-list">
                    <li>Menjaga kualitas dan legalitas konten yang dipublikasikan.</li>
                    <li>Tidak memuat konten yang menipu, melanggar hukum, atau merugikan pengguna lain.</li>
                    <li>Siap merespons klarifikasi jika terjadi laporan, sengketa, atau permintaan verifikasi.</li>
                </ul>
            </article>

            <article class="support-panel legal-card legal-card-highlight">
                <h2 class="support-title">Hak platform</h2>
                <p class="support-copy">DAYAKARYA berhak meninjau, menunda tayang, menurunkan, atau membatasi monetisasi konten jika ditemukan pelanggaran kebijakan, risiko hukum, atau penyalahgunaan sistem.</p>
            </article>
        </div>
    </div>
</section>
@endsection
