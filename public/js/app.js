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
    return `
      <a class="work-card" href="/karya/${w.slug}">
        <div class="work-cover" style="${w.cover ? `background-image:url('${w.cover}');background-size:cover` : ''}">
          <span class="type-tag">${this.typeLabel(w.type)}</span>${free}
        </div>
        <div class="work-body">
          <h3>${w.title}</h3>
          <div class="work-meta">✍️ ${w.creator?.name ?? 'Kreator'} · ${w.views ?? 0}x dibaca</div>
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
          <div class="emoji">🖋️</div><h3>Belum ada karya</h3>
          <p>Jadilah yang pertama berbagi karya di sini.</p>
          <a href="/daftar" class="btn btn-gold">Mulai Berkarya</a></div>`;
        return;
      }
      el.innerHTML = items.map(w => this.workCard(w)).join('');
    } catch (e) {
      el.innerHTML = `<div class="state" style="grid-column:1/-1">
        <div class="emoji">⚠️</div><h3>Gagal memuat</h3>
        <p>Periksa koneksimu, lalu coba lagi.</p></div>`;
    }
  },

  async refreshCredit() {
    const val = document.querySelector('#credit-value');
    if (!val || !this.token()) return;
    try {
      const w = await this.get('/wallet');
      val.textContent = (w.credit_balance ?? 0).toLocaleString('id-ID') + ' Credit';
    } catch (_) {}
  },
};
window.DK = DK;

// Daftarkan service worker (PWA)
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
}
