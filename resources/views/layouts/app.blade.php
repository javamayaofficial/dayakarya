<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#14223c">
    <title>@yield('title', 'Dayakarya — Berkarya, Berdampak, Berpenghasilan')</title>
    <meta name="description" content="@yield('desc', 'Platform kreator Indonesia untuk menulis, mendongeng, dan berpenghasilan dari karya digital.')">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/img/icon-192.png">

    {{-- Fonts (fallback aman bila offline) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/app.css">
    @stack('head')
</head>
<body>

    {{-- Top bar --}}
    <header class="topbar">
        <div class="container row">
            <a href="{{ route('home') }}" class="brand">
                <span class="spine"></span> Dayakarya
            </a>
            <a href="{{ route('wallet') }}" class="credit-pill" id="credit-pill">
                <span class="dot"></span> <span id="credit-value">Credit</span>
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <p class="foot container">
        Dayakarya oleh Yayasan Pondok Daya Cipta Nusantara &middot; &copy; {{ date('Y') }}
    </p>

    {{-- Bottom nav (rasa aplikasi) --}}
    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="ic">⌂</span> Beranda
        </a>
        <a href="{{ route('explore') }}" class="{{ request()->routeIs('explore') ? 'active' : '' }}">
            <span class="ic">🔍</span> Jelajah
        </a>
        <a href="{{ route('creator.dashboard') }}" class="fab" title="Buat Karya">
            <span class="ic">＋</span>
        </a>
        <a href="{{ route('wallet') }}" class="{{ request()->routeIs('wallet') ? 'active' : '' }}">
            <span class="ic">◈</span> Wallet
        </a>
        <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
            <span class="ic">◔</span> Akun
        </a>
    </nav>

    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
