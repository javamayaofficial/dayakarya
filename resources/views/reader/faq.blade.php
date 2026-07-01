@extends('layouts.app')
@section('title', 'Pertanyaan Umum — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">FAQ Dayakarya</span>
            <h1>Pertanyaan yang paling sering muncul, dijawab dengan lebih jelas dan lebih rapi.</h1>
            <p>Halaman ini membantu pengguna memahami alur Dayakarya, mulai dari karya, top up, monetisasi, sampai pengalaman menikmati konten premium.</p>
        </div>
        <div id="faq-list" class="support-panel">
            <div class="state state-panel"><div class="emoji">💬</div><p>Memuat FAQ…</p></div>
        </div>
    </div>
</section>
@endsection
