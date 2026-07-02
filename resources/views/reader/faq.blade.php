@extends('layouts.app')
@section('title', 'Pertanyaan Umum — Dayakarya')
@section('body_class', 'page-support')
@section('content')
<section class="section">
    <div class="container support-container">
        <div class="support-hero">
            <span class="section-kicker">FAQ Dayakarya</span>
            <h1>Pertanyaan penting dijawab dengan singkat dan jelas.</h1>
            <p>Pahami cara kerja Dayakarya, dari katalog hingga top up.</p>
        </div>
        <div id="faq-list" class="support-panel">
            <div class="state state-panel"><div class="emoji">💬</div><p>Memuat FAQ…</p></div>
        </div>
    </div>
</section>
@endsection
