/* ============================================================
   DAYAKARYA — Frontend helper (vanilla JS)
   Menghubungkan frontend PWA ke REST API. Aplikasi mobile nanti
   memakai endpoint yang persis sama.
   ============================================================ */
const DK = {
  api: '/api/v1',
  intendedKey: 'dk_intended_url',
  topupReturnKey: 'dk_post_topup_return_url',
  pendingTopupKey: 'dk_pending_topup',
  creatorRoles: ['creator', 'listener', 'admin', 'operator'],

  token() { return localStorage.getItem('dk_token'); },
  setToken(t) { localStorage.setItem('dk_token', t); },
  clearToken() { localStorage.removeItem('dk_token'); },

  normalizeInternalUrl(target, fallback = '/') {
    if (typeof target !== 'string' || target.trim() === '') {
      return fallback;
    }

    try {
      const url = new URL(target, window.location.origin);
      if (url.origin !== window.location.origin) {
        return fallback;
      }

      return `${url.pathname}${url.search}${url.hash}` || fallback;
    } catch (_) {
      return fallback;
    }
  },

  currentUrl() {
    return this.normalizeInternalUrl(window.location.href, '/');
  },

  setStoredUrl(key, target, fallback = '/') {
    const resolved = this.normalizeInternalUrl(target, fallback);
    localStorage.setItem(key, resolved);
    return resolved;
  },

  getStoredUrl(key, fallback = '/') {
    return this.normalizeInternalUrl(localStorage.getItem(key), fallback);
  },

  clearStoredUrl(key) {
    localStorage.removeItem(key);
  },

  setIntendedUrl(target) {
    const resolved = this.normalizeInternalUrl(target, this.currentUrl());
    if (resolved.startsWith('/masuk') || resolved.startsWith('/auth/google')) {
      return resolved;
    }

    return this.setStoredUrl(this.intendedKey, resolved, '/');
  },

  getIntendedUrl(fallback = '/creator') {
    const params = new URLSearchParams(window.location.search);
    const queryTarget = params.get('return');
    if (queryTarget) {
      return this.normalizeInternalUrl(queryTarget, fallback);
    }

    return this.getStoredUrl(this.intendedKey, fallback);
  },

  consumeIntendedUrl(fallback = '/creator') {
    const target = this.getIntendedUrl(fallback);
    this.clearStoredUrl(this.intendedKey);
    return target;
  },

  clearIntendedUrl() {
    this.clearStoredUrl(this.intendedKey);
  },

  setTopupReturnUrl(target) {
    return this.setStoredUrl(this.topupReturnKey, target, '/wallet');
  },

  getTopupReturnUrl(fallback = '/wallet') {
    const params = new URLSearchParams(window.location.search);
    const queryTarget = params.get('return');
    if (queryTarget) {
      return this.normalizeInternalUrl(queryTarget, fallback);
    }

    return this.getStoredUrl(this.topupReturnKey, fallback);
  },

  consumeTopupReturnUrl(fallback = '/wallet') {
    const target = this.getTopupReturnUrl(fallback);
    this.clearStoredUrl(this.topupReturnKey);
    return target;
  },

  clearTopupReturnUrl() {
    this.clearStoredUrl(this.topupReturnKey);
  },

  setPendingTopup(payload = {}) {
    try {
      const nextPayload = {
        payment_id: payload.payment_id ? Number(payload.payment_id) : null,
        order_id: payload.order_id ? String(payload.order_id) : '',
        amount: payload.amount ? Number(payload.amount) : 0,
        credit_amount: payload.credit_amount ? Number(payload.credit_amount) : 0,
        provider: payload.provider ? String(payload.provider) : '',
        return_to: this.normalizeInternalUrl(payload.return_to || '/wallet', '/wallet'),
        created_at: payload.created_at ? Number(payload.created_at) : Date.now(),
      };

      localStorage.setItem(this.pendingTopupKey, JSON.stringify(nextPayload));
      return nextPayload;
    } catch (_) {
      return null;
    }
  },

  getPendingTopup() {
    try {
      const raw = localStorage.getItem(this.pendingTopupKey);
      if (!raw) return null;

      const parsed = JSON.parse(raw);
      if (!parsed || typeof parsed !== 'object') return null;

      return {
        payment_id: parsed.payment_id ? Number(parsed.payment_id) : null,
        order_id: parsed.order_id ? String(parsed.order_id) : '',
        amount: parsed.amount ? Number(parsed.amount) : 0,
        credit_amount: parsed.credit_amount ? Number(parsed.credit_amount) : 0,
        provider: parsed.provider ? String(parsed.provider) : '',
        return_to: this.normalizeInternalUrl(parsed.return_to || '/wallet', '/wallet'),
        created_at: parsed.created_at ? Number(parsed.created_at) : 0,
      };
    } catch (_) {
      return null;
    }
  },

  clearPendingTopup() {
    localStorage.removeItem(this.pendingTopupKey);
  },

  loginUrl(target) {
    const intended = this.setIntendedUrl(target || this.currentUrl());
    const query = new URLSearchParams();

    if (intended) {
      query.set('return', intended);
    }

    return '/masuk' + (query.toString() ? `?${query.toString()}` : '');
  },

  redirectToLogin(target) {
    window.location.href = this.loginUrl(target);
  },

  walletUrl(target, extraParams = {}) {
    const query = new URLSearchParams();
    const returnTarget = target ? this.setTopupReturnUrl(target) : null;

    if (returnTarget && returnTarget !== '/wallet') {
      query.set('return', returnTarget);
    }

    Object.entries(extraParams).forEach(([key, value]) => {
      if (value === undefined || value === null || value === '') return;
      query.set(key, String(value));
    });

    return '/wallet' + (query.toString() ? `?${query.toString()}` : '');
  },

  redirectToWallet(target, extraParams = {}) {
    window.location.href = this.walletUrl(target, extraParams);
  },

  roleList(source) {
    if (Array.isArray(source)) {
      return source.map((role) => String(role));
    }

    return [];
  },

  hasCreatorAccessFromRoles(roles) {
    return this.roleList(roles).some((role) => this.creatorRoles.includes(role));
  },

  memberHomeFromRoles(roles, fallback = '/explore') {
    return this.hasCreatorAccessFromRoles(roles) ? '/creator' : fallback;
  },

  headers() {
    const h = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
    const t = this.token();
    if (t) h['Authorization'] = 'Bearer ' + t;
    return h;
  },

  async get(path) {
    const res = await fetch(this.api + path, { headers: this.headers() });
    return res.json();
  },

  async post(path, body) {
    const res = await fetch(this.api + path, {
      method: 'POST', headers: this.headers(), body: JSON.stringify(body || {}),
    });
    return { ok: res.ok, status: res.status, data: await res.json() };
  },

  async logout() {
    if (this.token()) {
      try {
        await this.post('/auth/logout');
      } catch (_) {}
    }

    this.clearToken();
    return true;
  },

  typeLabel(t) {
    return ({
      cerpen: 'Cerpen', novel: 'Novel', podcast: 'Podcast',
      audio_story: 'Audio', video_series: 'Video Series', dongeng: 'Dongeng', motivasi: 'Motivasi', audiobook: 'Audiobook',
    })[t] || t;
  },

  workCard(w) {
    const free = (w.chapters_free_count ?? 0) > 0 ? '<span class="free-tag">Gratis</span>' : '';
    const creator = w.creator?.name ?? 'Kreator';
    const views = (w.views ?? 0).toLocaleString('id-ID');
    const coverStyle = w.cover
      ? `background-image:url('${w.cover}');background-size:cover;background-position:center;`
      : '';

    return `
      <a class="work-card work-card-premium" href="/karya/${w.slug}">
        <div class="work-cover" style="${coverStyle}">
          <span class="type-tag">${this.typeLabel(w.type)}</span>${free}
          <div class="cover-fade"></div>
          <div class="cover-meta">
            <span class="cover-pill">${creator}</span>
          </div>
        </div>
        <div class="work-body">
          <h3>${w.title}</h3>
          <div class="work-meta">✍️ ${creator} · ${views}x dibaca</div>
          <div class="work-card-footer">
            <span class="read-link">Masuk ke karya</span>
            <span class="read-stat">${(w.chapters_free_count ?? 0) > 0 ? 'Ada akses gratis' : 'Siap premium'}</span>
          </div>
        </div>
      </a>`;
  },

  async loadWorks({ trending = 0, type = '', search = '', target } = {}) {
    const el = document.querySelector(target);
    if (!el) return;
    try {
      const q = new URLSearchParams();
      if (trending) q.set('trending', '1');
      if (type) q.set('type', type);
      if (search) q.set('search', search);
      const json = await this.get('/works?' + q.toString());
      const items = json.data ?? [];
      if (!items.length) {
        el.innerHTML = search
          ? `<div class="state" style="grid-column:1/-1">
              <div class="emoji">🔍</div><h3>Belum ketemu</h3>
              <p>Coba ganti kata kunci atau pilih tipe karya lain.</p></div>`
          : `<div class="state" style="grid-column:1/-1">
              <div class="emoji">🖋️</div><h3>Belum ada karya yang tampil di sini</h3>
              <p>Kalau mau, kamu bisa jadi salah satu yang pertama mengisinya.</p>
              <a href="/daftar" class="btn btn-gold">Mulai Upload Karya</a></div>`;
        return;
      }
      el.innerHTML = items.map(w => this.workCard(w)).join('');
    } catch (e) {
      el.innerHTML = `<div class="state" style="grid-column:1/-1">
        <div class="emoji">⚠️</div><h3>Karyanya belum bisa dimuat</h3>
        <p>Coba cek koneksi dulu, lalu buka lagi sebentar ya.</p></div>`;
    }
  },

  async refreshCredit() {
    const val = document.querySelector('#credit-value');
    const pill = document.querySelector('#credit-pill');
    if (!val || !pill) return;

    if (!this.token()) {
      val.textContent = 'Masuk';
      pill.setAttribute('href', '/masuk');
      pill.dataset.authState = 'guest';
      return;
    }

    try {
      const w = await this.get('/wallet');
      val.textContent = (w.credit_balance ?? 0).toLocaleString('id-ID') + ' Credit';
      pill.setAttribute('href', '/wallet');
      pill.dataset.authState = 'member';
    } catch (_) {}
  },
};
window.DK = DK;

let deferredInstallPrompt = null;

function ensureAppStatus() {
  let status = document.querySelector('[data-app-status]');
  if (status) return status;

  status = document.createElement('div');
  status.className = 'app-status';
  status.dataset.appStatus = 'true';
  status.setAttribute('role', 'status');
  status.setAttribute('aria-live', 'polite');
  document.body.appendChild(status);
  return status;
}

function hideAppStatus() {
  const status = document.querySelector('[data-app-status]');
  if (!status) return;
  clearTimeout(hideAppStatus.timer);
  status.classList.remove('is-visible');
}

function showAppStatus(message, { tone = 'default', duration = 2400, sticky = false } = {}) {
  const status = ensureAppStatus();
  clearTimeout(hideAppStatus.timer);

  status.classList.remove('is-offline', 'is-online', 'is-success');
  if (tone === 'offline') status.classList.add('is-offline');
  if (tone === 'online') status.classList.add('is-online');
  if (tone === 'success') status.classList.add('is-success');

  status.textContent = message;
  status.classList.add('is-visible');

  if (!sticky) {
    hideAppStatus.timer = window.setTimeout(() => {
      status.classList.remove('is-visible');
    }, duration);
  }
}

function isStandaloneMode() {
  return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
}

function getInstallFallbackMessage() {
  const ua = navigator.userAgent || '';
  if (isStandaloneMode()) {
    return 'Dayakarya sudah terpasang di perangkat ini.';
  }

  if (/iphone|ipad|ipod/i.test(ua)) {
    return 'Kalau pakai iPhone atau iPad, buka Share lalu pilih Add to Home Screen.';
  }

  if (/android/i.test(ua)) {
    return 'Kalau pakai Android, buka menu browser lalu pilih Install App atau Tambahkan ke layar utama.';
  }

  return 'Buka menu browser, lalu pilih Install App atau Add to Home Screen.';
}

function updateInstallButtons() {
  const buttons = document.querySelectorAll('[data-install-app]');
  const notes = document.querySelectorAll('[data-install-note]');
  const installed = isStandaloneMode();
  const label = installed
    ? 'Sudah Terpasang'
    : deferredInstallPrompt
      ? 'Install App'
      : /iphone|ipad|ipod/i.test(navigator.userAgent || '')
        ? 'Simpan ke Home Screen'
        : 'Pasang Dayakarya';
  const note = installed
    ? 'Dayakarya sudah siap dipakai dari layar utama.'
    : deferredInstallPrompt
      ? 'Pasang Dayakarya ke layar utama biar bukanya lebih cepat dan terasa seperti aplikasi.'
      : 'Kalau tombol install belum muncul, tenang, kamu tetap bisa simpan Dayakarya ke layar utama lewat menu browser.';

  document.documentElement.classList.toggle('pwa-installable', Boolean(deferredInstallPrompt));

  buttons.forEach((button) => {
    const labelEl = button.querySelector('[data-install-label]');
    if (labelEl) labelEl.textContent = label;
    button.disabled = installed;
  });

  notes.forEach((item) => {
    item.textContent = note;
  });
}

function renderConnectivityStatus(forceVisible = false) {
  const offline = !navigator.onLine;

  document.body.classList.toggle('is-offline', offline);
  if (offline) {
    showAppStatus('Mode offline aktif. Halaman yang sudah tersimpan tetap bisa dibuka.', {
      tone: 'offline',
      sticky: true,
    });
    return;
  }

  if (forceVisible) {
    showAppStatus('Koneksi kembali stabil.', {
      tone: 'online',
      duration: 2200,
    });
  } else {
    hideAppStatus();
  }
}

function initConnectivityStatus() {
  if (!document.body) return;
  if (!navigator.onLine) renderConnectivityStatus(true);

  window.addEventListener('offline', () => renderConnectivityStatus(true));
  window.addEventListener('online', () => renderConnectivityStatus(true));
}

function initCreditPill() {
  const pill = document.querySelector('#credit-pill');
  if (!pill) return;

  if (!DK.token()) {
    pill.setAttribute('href', DK.loginUrl(DK.currentUrl()));
    pill.dataset.authState = 'guest';
  }

  if (pill.dataset.creditBound === 'true') return;
  pill.dataset.creditBound = 'true';

  pill.addEventListener('click', (event) => {
    if (DK.token()) return;

    event.preventDefault();
    showAppStatus('Masuk dengan akun pengguna untuk melihat saldo credit dan membuka wallet.', {
      duration: 3200,
    });
    window.setTimeout(() => {
      DK.redirectToLogin(DK.currentUrl());
    }, 260);
  });
}

async function resolveInternalArea() {
  if (!DK.token()) {
    return { authenticated: false, href: '/masuk', label: 'Akun' };
  }

  const me = await DK.get('/auth/me');
  if (!me?.user?.id) {
    DK.clearToken();
    return { authenticated: false, href: '/masuk', label: 'Akun' };
  }

  return {
    authenticated: true,
    href: DK.memberHomeFromRoles(me.roles, '/explore'),
    label: 'Akun',
    roles: DK.roleList(me.roles),
    creatorAccess: DK.hasCreatorAccessFromRoles(me.roles),
    user: me.user,
  };
}

function setNavItem(item, { href, icon, label, hidden = false, fab = false, matchPrefix = '', title = '' }) {
  if (!item) return;

  item.hidden = hidden;
  if (href) item.setAttribute('href', href);
  if (title) item.setAttribute('title', title);
  item.dataset.matchPrefix = matchPrefix;
  item.classList.toggle('fab', fab);

  const iconEl = item.querySelector('[data-nav-icon]');
  const labelEl = item.querySelector('[data-nav-label]');

  if (iconEl && icon) iconEl.textContent = icon;
  if (labelEl) {
    labelEl.textContent = label;
    labelEl.hidden = fab;
  }
}

function applyBottomNavActiveState() {
  const currentPath = window.location.pathname;
  document.querySelectorAll('.bottom-nav a').forEach((item) => {
    item.classList.remove('active');

    const matchPrefix = item.dataset.matchPrefix;
    if (!matchPrefix || item.hidden) return;

    if (matchPrefix === '/') {
      if (currentPath === '/') item.classList.add('active');
      return;
    }

    if (currentPath === matchPrefix || currentPath.startsWith(matchPrefix + '/')) {
      item.classList.add('active');
    }
  });
}

function setShellMode(mode, session = null) {
  const shellBadge = document.querySelector('#shell-badge');
  const body = document.body;
  if (!body) return;

  if (mode === 'member') {
    body.classList.add('is-member-area');
    body.dataset.memberRole = 'member';

    if (shellBadge) {
      shellBadge.hidden = false;
      shellBadge.textContent = 'Member Area';
    }
    return;
  }

  body.classList.remove('is-member-area');
  delete body.dataset.memberRole;
  if (shellBadge) {
    shellBadge.hidden = true;
    shellBadge.textContent = 'Member Area';
  }
}

function initGuestNavigation() {
  const brandLink = document.querySelector('#brand-link');
  const primaryNav = document.querySelector('#primary-nav');
  const secondaryNav = document.querySelector('#secondary-nav');
  const middleNav = document.querySelector('#middle-nav');
  const walletNav = document.querySelector('#wallet-nav');
  const accountNav = document.querySelector('#account-nav');
  const accountLabel = accountNav?.querySelector('[data-account-label]');
  const accountIcon = accountNav?.querySelector('[data-nav-icon]');

  if (brandLink) {
    brandLink.setAttribute('href', brandLink.dataset.guestHref || '/');
  }

  setNavItem(primaryNav, {
    href: primaryNav?.dataset.guestHref || '/',
    icon: '⌂',
    label: 'Beranda',
    matchPrefix: '/',
  });

  setNavItem(secondaryNav, {
    href: secondaryNav?.dataset.guestHref || '/explore',
    icon: '🔍',
    label: 'Jelajah',
    matchPrefix: '/explore',
  });

  setNavItem(middleNav, {
    href: '/creator',
    icon: '＋',
    label: 'Buat',
    hidden: true,
    fab: true,
    matchPrefix: '/creator',
    title: 'Buat Karya',
  });

  if (walletNav) {
    walletNav.dataset.matchPrefix = '/wallet';
  }

  if (accountNav) {
    accountNav.setAttribute('href', accountNav.dataset.guestHref || '/masuk');
    accountNav.dataset.action = 'link';
    accountNav.dataset.matchPrefix = '/masuk';
  }
  if (accountLabel) accountLabel.textContent = 'Akun';
  if (accountIcon) accountIcon.textContent = '◔';

  setShellMode('guest');
  applyBottomNavActiveState();
}

function initMemberNavigation(session) {
  const dashboardHref = session?.href || '/explore';
  const creatorHref = '/creator';
  const creatorAccess = Boolean(session?.creatorAccess);
  const brandLink = document.querySelector('#brand-link');
  const primaryNav = document.querySelector('#primary-nav');
  const secondaryNav = document.querySelector('#secondary-nav');
  const middleNav = document.querySelector('#middle-nav');
  const walletNav = document.querySelector('#wallet-nav');
  const accountNav = document.querySelector('#account-nav');
  const accountLabel = accountNav?.querySelector('[data-account-label]');
  const accountIcon = accountNav?.querySelector('[data-nav-icon]');

  if (brandLink) {
    brandLink.setAttribute('href', dashboardHref);
  }

  setNavItem(primaryNav, {
    href: dashboardHref,
    icon: creatorAccess ? '◫' : '⌂',
    label: creatorAccess ? 'Dashboard' : 'Jelajah',
    matchPrefix: creatorAccess ? '/creator' : '/explore',
  });

  setNavItem(secondaryNav, {
    href: creatorAccess ? '/explore' : '/leaderboard',
    icon: creatorAccess ? '🔍' : '🏆',
    label: creatorAccess ? 'Jelajah' : 'Leaderboard',
    matchPrefix: creatorAccess ? '/explore' : '/leaderboard',
  });

  setNavItem(middleNav, {
    href: creatorAccess ? `${creatorHref}#creator-quick-create` : creatorHref,
    icon: '＋',
    label: creatorAccess ? 'Buat' : 'Mulai',
    hidden: false,
    fab: true,
    matchPrefix: '/creator',
    title: creatorAccess ? 'Buat Karya Baru' : 'Mulai Berkarya',
  });

  if (walletNav) {
    walletNav.dataset.matchPrefix = '/wallet';
  }

  if (accountNav) {
    accountNav.setAttribute('href', '#logout');
    accountNav.dataset.action = 'logout';
    accountNav.dataset.matchPrefix = '';
  }
  if (accountLabel) accountLabel.textContent = 'Keluar';
  if (accountIcon) accountIcon.textContent = '⇥';

  setShellMode('member', session);
  applyBottomNavActiveState();
}

async function initAccountNav() {
  const accountNav = document.querySelector('#account-nav');
  if (!accountNav) return;

  if (!DK.token()) {
    initGuestNavigation();
    return;
  }

  const session = await resolveInternalArea();
  if (!session.authenticated) {
    initGuestNavigation();
    return;
  }

  initMemberNavigation(session);

  if (accountNav.dataset.bound === 'true') return;
  accountNav.dataset.bound = 'true';

  accountNav.addEventListener('click', async (event) => {
    if (accountNav.dataset.action !== 'logout') return;

    event.preventDefault();
    showAppStatus('Sedang keluar dari akun...', {
      tone: 'success',
      duration: 1800,
    });

    await DK.logout();
    window.location.href = '/masuk';
  });
}

async function initLogoutButton() {
  const logoutButton = document.querySelector('#logout-button');
  if (!logoutButton) return;

  if (!DK.token()) {
    logoutButton.hidden = true;
    return;
  }

  const session = await resolveInternalArea();
  if (!session.authenticated) {
    logoutButton.hidden = true;
    return;
  }

  logoutButton.hidden = false;

  if (logoutButton.dataset.bound === 'true') return;
  logoutButton.dataset.bound = 'true';

  logoutButton.addEventListener('click', async () => {
    logoutButton.disabled = true;
    showAppStatus('Sedang keluar dari akun...', {
      tone: 'success',
      duration: 1800,
    });

    await DK.logout();
    window.location.href = '/masuk';
  });
}

async function initAuthOnlyVisibility() {
  const authOnlyItems = document.querySelectorAll('[data-auth-only]');
  if (!authOnlyItems.length) return;

  if (!DK.token()) {
    authOnlyItems.forEach((item) => { item.hidden = true; });
    return;
  }

  const session = await resolveInternalArea();
  const visible = Boolean(session.authenticated);

  authOnlyItems.forEach((item) => {
    item.hidden = !visible;
  });
}

async function attemptInstall() {
  if (isStandaloneMode()) return 'installed';
  if (!deferredInstallPrompt) return 'unavailable';

  deferredInstallPrompt.prompt();
  const choice = await deferredInstallPrompt.userChoice;
  deferredInstallPrompt = null;
  updateInstallButtons();

  return choice.outcome === 'accepted' ? 'accepted' : 'dismissed';
}

async function promptInstall() {
  return (await attemptInstall()) === 'accepted';
}

window.DK.promptInstall = promptInstall;
window.DK.installApp = attemptInstall;

window.addEventListener('beforeinstallprompt', (event) => {
  event.preventDefault();
  deferredInstallPrompt = event;
  updateInstallButtons();
  window.dispatchEvent(new CustomEvent('dk:pwa-installable'));
});

window.addEventListener('appinstalled', () => {
  deferredInstallPrompt = null;
  updateInstallButtons();
  showAppStatus('Dayakarya berhasil dipasang. Buka lagi dari homescreen kapan saja.', {
    tone: 'success',
    duration: 3200,
  });
});

function initInstallButtons() {
  const buttons = document.querySelectorAll('[data-install-app]');
  if (!buttons.length) return;

  buttons.forEach((button) => {
    if (button.dataset.installBound === 'true') return;
    button.dataset.installBound = 'true';
    button.addEventListener('click', async () => {
      const result = await attemptInstall();

      if (result === 'accepted') return;
      if (result === 'installed') {
        showAppStatus('Dayakarya sudah terpasang di perangkat ini.', {
          tone: 'success',
        });
        return;
      }

      if (result === 'unavailable') {
        showAppStatus(getInstallFallbackMessage(), {
          duration: 3600,
        });
      }
    });
  });

  updateInstallButtons();
}

function initOauthNotice() {
  const message = sessionStorage.getItem('dk_oauth_notice');
  if (!message) return;

  sessionStorage.removeItem('dk_oauth_notice');
  showAppStatus(message, {
    tone: 'success',
    duration: 3600,
  });
}

// Daftarkan service worker (PWA)
if ('serviceWorker' in navigator) {
  window.addEventListener('load', async () => {
    try {
      const registration = await navigator.serviceWorker.register('/sw.js', { scope: '/' });
      window.setTimeout(() => registration.update().catch(() => {}), 1200);
    } catch (_) {}
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initConnectivityStatus();
  initCreditPill();
  DK.refreshCredit();
  initAuthOnlyVisibility();
  initAccountNav();
  initLogoutButton();
  initInstallButtons();
  initOauthNotice();
});
