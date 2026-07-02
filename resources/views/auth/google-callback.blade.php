@extends('layouts.app')
@section('title', 'Masuk Dengan Google — Dayakarya')
@section('body_class', 'page-auth')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <aside class="auth-aside">
                <span class="section-kicker">Google Sign-In</span>
                <h1>Masuk lebih cepat tanpa mengorbankan rasa aman dan kenyamanan.</h1>
                <p>Dayakarya sedang menyiapkan akun Anda dari autentikasi Google agar akses ke karya, wallet, dan fitur kreator terasa lebih ringkas sejak awal.</p>
            </aside>

            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Google Terhubung</span>
                    <h2>Menyelesaikan login Anda</h2>
                    <p>{{ $notice }}</p>
                </div>
                <div class="state-panel">
                    <div class="state">
                        <div class="emoji">🔐</div>
                        <h3>Menyiapkan sesi Dayakarya</h3>
                        <p>Jika proses ini tidak berjalan otomatis, gunakan tombol di bawah untuk lanjut ke area akun Anda.</p>
                        <a href="{{ $redirectTo }}" class="btn btn-primary">Lanjut ke Dayakarya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  localStorage.setItem('dk_token', @json($token));
  sessionStorage.setItem('dk_oauth_notice', @json($notice));
  setTimeout(() => {
    window.location.href = @json($redirectTo);
  }, 600);
</script>
@endpush
