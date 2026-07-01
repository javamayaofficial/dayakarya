@extends('layouts.app')
@section('title', 'Pertanyaan Umum — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">FAQ Dayakarya</span>
            <h1>Pertanyaan penting dijawab dengan bahasa yang lebih jelas, agar keputusan terasa lebih yakin.</h1>
            <p>Gunakan halaman ini untuk memahami cara kerja Dayakarya, mulai dari katalog karya, top up, monetisasi, sampai alasan mengapa pengalaman premium di sini terasa lebih tertata.</p>
        </div>
        <div id="faq-list" class="support-panel">
            <div class="state state-panel"><div class="emoji">💬</div><p>Memuat FAQ…</p></div>
        </div>
    </div>
</section>
@endsection
