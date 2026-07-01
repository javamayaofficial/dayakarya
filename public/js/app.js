/* ============================================================
   DAYAKARYA — Frontend helper (vanilla JS)
   Menghubungkan frontend PWA ke REST API. Aplikasi mobile nanti
   memakai endpoint yang persis sama.
   ============================================================ */
const DK = {
  api: '/api/v1',

  token() { return localStorage.getItem('dk_token'); },
  setToken(t) { localStorage.setItem('dk_token', t); },
  clearToken() { localStorage.removeItem('dk_token'); },

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

  typeLabel(t) {
    return ({
      cerpen: 'Cerpen', novel: 'Novel', podcast: 'Podcast',
      audio_story: 'Audio', dongeng: 'Dongeng', motivasi: 'Motivasi', audiobook: 'Audiobook',
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

  async loadWorks({ trending = 0, type = '', target } = {}) {
    const el = document.querySelector(target);
    if (!el) return;
    try {
      const q = new URLSearchParams();
      if (trending) q.set('trending', '1');
      if (type) q.set('type', type);
      const json = await this.get('/works?' + q.toString());
      const items = json.data ?? [];
      if (!items.length) {
        el.innerHTML = `<div class="state" style="grid-column:1/-1">
          <div class="emoji">🖋️</div><h3>Belum ada karya unggulan</h3>
          <p>Jadilah kreator pertama yang membangun katalog bernilai di sini.</p>
          <a href="/daftar" class="btn btn-gold">Mulai Bangun Karya</a></div>`;
        return;
      }
      el.innerHTML = items.map(w => this.workCard(w)).join('');
    } catch (e) {
      el.innerHTML = `<div class="state" style="grid-column:1/-1">
        <div class="emoji">⚠️</div><h3>Katalog belum berhasil dimuat</h3>
        <p>Periksa koneksi Anda, lalu coba lagi dalam beberapa saat.</p></div>`;
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
    return 'Di iPhone atau iPad, buka Share lalu pilih Add to Home Screen.';
  }

  if (/android/i.test(ua)) {
    return 'Gunakan menu browser lalu pilih Install App atau Tambahkan ke layar utama.';
  }

  return 'Gunakan menu browser Anda lalu pilih Install App atau Add to Home Screen.';
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
    ? 'Dayakarya sudah aktif sebagai aplikasi di perangkat ini.'
    : deferredInstallPrompt
      ? 'Pasang Dayakarya ke homescreen untuk akses lebih cepat, lebih stabil, dan terasa seperti aplikasi.'
      : 'Jika prompt otomatis belum muncul, Anda tetap bisa menambahkan Dayakarya ke layar utama dari menu browser.';

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
    pill.setAttribute('href', '/masuk');
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
      window.location.href = '/masuk';
    }, 260);
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
  initInstallButtons();
  initOauthNotice();
});
