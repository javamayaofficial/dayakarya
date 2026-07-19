@extends('layouts.app')
@section('title', 'Masuk — Dayakarya')
@section('body_class', 'page-auth page-auth-simple')

@section('content')
<section class="section auth-section">
    <div class="container auth-container">
        <div class="auth-shell">
            <div class="auth-card card">
                <div class="auth-card-head">
                    <span class="mini-label mini-label-dark">Login Dayakarya</span>
                    <h2>Selamat datang kembali</h2>
                </div>

                <div id="msg"></div>
                @if (session('google_auth_error'))
                    <div class="alert alert-error">{{ session('google_auth_error') }}</div>
                @endif
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="email" placeholder="nama@email.com" autocomplete="email">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" id="password" placeholder="Kata sandi" autocomplete="current-password">
                </div>
                <button class="btn btn-primary btn-block" onclick="doLogin()">Masuk ke Akun</button>
                <a href="{{ route('auth.google.redirect') }}" class="btn btn-google btn-block auth-google-btn" id="google-login-link">
                    <span class="btn-google-mark">G</span>
                    <span>Masuk dengan Google</span>
                </a>
                <div class="auth-meta">
                    <span>Belum punya akun?</span>
                    <a href="/daftar">Buat akun gratis</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const loginParams = new URLSearchParams(window.location.search);
  const loginReturnTarget = loginParams.get('return');
  const googleLoginLink = document.querySelector('#google-login-link');

  function memberFallbackTarget(roles = [], fallback = '/explore') {
    return DK.memberHomeFromRoles(roles, fallback);
  }

  function resolveLoginRedirectTarget(defaultTarget = '/explore') {
    return loginReturnTarget ? DK.consumeIntendedUrl(defaultTarget) : defaultTarget;
  }

  function prepareLoginEntry() {
    if (loginReturnTarget) {
      DK.setIntendedUrl(loginReturnTarget);
      if (googleLoginLink) {
        googleLoginLink.href = '/auth/google/redirect?return=' + encodeURIComponent(loginReturnTarget);
      }
      return;
    }

    DK.clearIntendedUrl();
  }

  async function redirectAuthenticatedSession() {
    if (!DK.token()) return;

    const me = await DK.get('/auth/me');
    if (!me?.user?.id) {
      DK.clearToken();
      return;
    }

    location.href = resolveLoginRedirectTarget(memberFallbackTarget(me.roles, '/explore'));
  }

  async function doLogin() {
    const msg = document.querySelector('#msg');
    const { ok, data } = await DK.post('/auth/login', {
      email: document.querySelector('#email').value,
      password: document.querySelector('#password').value,
    });
    if (ok) {
      DK.setToken(data.token);
      msg.innerHTML = '<div class="alert alert-success">Berhasil masuk. Mengalihkan…</div>';
      const redirectTarget = resolveLoginRedirectTarget(data.redirect_to || memberFallbackTarget(data.roles, '/explore'));
      setTimeout(() => location.href = redirectTarget, 700);
    } else {
      const err = data.errors?.email?.[0] || data.message || 'Gagal masuk.';
      msg.innerHTML = `<div class="alert alert-error">${err}</div>`;
    }
  }

  prepareLoginEntry();
  redirectAuthenticatedSession();
</script>
@endpush
