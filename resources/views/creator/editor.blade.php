@extends('layouts.app')
@section('title', 'Lanjut Edit Draft — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Editor Draft</span>
                <h1>Lanjutkan draft tanpa kehilangan fokus kerja.</h1>
                <p>Editor ini saya padatkan supaya kamu lebih cepat tahu progres draft, lompat ke bagian penting, lalu simpan atau tayangkan tanpa bingung.</p>
                <div class="creator-hero-actions">
                    <a href="{{ route('creator.dashboard') }}" class="btn btn-ghost">Kembali ke Dashboard</a>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Fokus Produksi</span>
                <h2>Tulis inti karya dulu, finishing belakangan.</h2>
                <p>Urutan ternyaman di editor ini: ringkasan, part aktif, isi karya, lalu cek readiness sebelum publish.</p>
            </div>
        </div>

        <div class="creator-editor-layout">
            <div class="creator-panel card">
                <div class="section-head section-head-premium">
                    <div>
                        <span class="section-kicker">Draft Kamu</span>
                        <h2 id="editor-heading">Memuat draft…</h2>
                    </div>
                </div>
                <div id="editor-msg"></div>
                <div class="creator-editor-command">
                    <div class="creator-editor-command-head">
                        <div>
                            <span class="section-kicker">Status Draft</span>
                            <strong id="creator-editor-status-label">Memuat status draft…</strong>
                            <p id="creator-editor-status-copy">Editor akan menampilkan prioritas kerja setelah draft selesai dimuat.</p>
                        </div>
                        <div class="creator-editor-command-meta">
                            <span id="creator-editor-mode-pill">Mode karya</span>
                            <span id="creator-editor-part-pill">Part aktif</span>
                            <span id="creator-editor-access-pill">Akses part</span>
                        </div>
                    </div>
                    <div class="creator-editor-jump-links">
                        <a href="#editor-section-summary">Ringkasan</a>
                        <a href="#editor-section-parts">Part</a>
                        <a href="#editor-section-content">Isi Karya</a>
                        <a href="#editor-section-finish">Finishing</a>
                        <a href="#editor-section-monetization">Monetisasi</a>
                        <a href="#editor-section-readiness">Checklist</a>
                    </div>
                </div>
                <div class="creator-production-focus">
                    <div class="creator-production-focus-head">
                        <strong>Fokus Produksi Cerpen</strong>
                        <span>Supaya tidak pecah fokus, pakai editor ini dengan urutan kerja yang pendek dan berulang.</span>
                    </div>
                    <div class="creator-production-focus-steps">
                        <span class="creator-production-step is-active">1. Rapikan ringkasan</span>
                        <span class="creator-production-step">2. Pilih part aktif</span>
                        <span class="creator-production-step">3. Tulis inti konten</span>
                        <span class="creator-production-step">4. Cek readiness</span>
                    </div>
                </div>

                <div class="creator-publish-checklist" id="editor-section-readiness">
                    <div class="creator-publish-checklist-head">
                        <strong>Checklist Siap Tayang</strong>
                        <span id="creator-publish-summary">Lengkapi dulu poin penting sebelum karya ditayangkan.</span>
                    </div>
                    <div class="creator-publish-checklist-grid" id="creator-publish-checklist">
                        <span class="creator-publish-item is-pending">Cover belum dicek</span>
                        <span class="creator-publish-item is-pending">Sinopsis belum dicek</span>
                        <span class="creator-publish-item is-pending">Part aktif belum dicek</span>
                        <span class="creator-publish-item is-pending">Akses belum dicek</span>
                    </div>
                </div>
                <div class="creator-finish-note" id="creator-publish-note">
                    Checklist siap tayang akan muncul saat draft mulai matang atau saat kamu membuka tahap monetisasi dan finishing.
                </div>

                <div class="creator-cover-panel" id="editor-section-finish">
                    <div class="creator-collapsible-head">
                        <div>
                            <span class="section-kicker">Finishing</span>
                            <strong>Cover dan tampilan karya</strong>
                            <span id="creator-cover-section-summary">Belum ada cover. Buka bagian ini saat kamu masuk tahap finishing.</span>
                        </div>
                        <button class="btn btn-ghost creator-collapsible-toggle" id="creator-cover-toggle" type="button" aria-expanded="false" onclick="toggleEditorSection('cover')">Buka Finishing</button>
                    </div>
                    <div class="creator-collapsible-body" id="creator-cover-section-body" hidden>
                        <div class="creator-cover-copy">
                            <span class="section-kicker">Cover Karya</span>
                            <h3>Pasang mockup cerpen atau cover utama karya di sini.</h3>
                            <p>Satu cover dipakai untuk seluruh karya, jadi Part 1 sampai Part berikutnya tetap punya wajah yang sama di katalog dan halaman detail.</p>
                            <p>Ukuran yang disarankan: <strong>1080 x 1440 piksel</strong> dengan rasio <strong>3:4</strong>. Minimal tetap aman di <strong>900 x 1200 piksel</strong> supaya hasilnya tidak pecah.</p>
                            <div class="creator-cover-specs">
                                <span class="creator-cover-spec">Rasio ideal: 3:4</span>
                                <span class="creator-cover-spec">Rekomendasi: 1080 x 1440 px</span>
                                <span class="creator-cover-spec">Minimal: 900 x 1200 px</span>
                                <span class="creator-cover-spec">Maks file: 4 MB</span>
                            </div>
                        </div>
                        <div class="creator-cover-layout">
                            <div class="creator-cover-preview-shell">
                                <div class="creator-cover-ratio-badge">Template 3:4</div>
                                <div class="creator-cover-safe-area" aria-hidden="true">
                                    <span>Area aman judul dan objek utama</span>
                                </div>
                                <img id="creator-cover-preview" class="creator-cover-preview" alt="Preview cover karya" hidden>
                                <div id="creator-cover-empty" class="creator-cover-empty">Belum ada cover. Upload mockup cerpen biar tampilan karya lebih meyakinkan.</div>
                            </div>
                            <div class="creator-cover-actions">
                                <label class="proof-upload-field creator-cover-field">
                                    <span>Pilih gambar cover</span>
                                    <input id="editor-cover-file" type="file" accept="image/png,image/jpeg,image/webp">
                                </label>
                                <button class="btn btn-ghost" id="editor-upload-cover" type="button" onclick="uploadWorkCover()">Upload Cover</button>
                                <div class="hint">Format yang disarankan: JPG, PNG, atau WEBP. Pakai ukuran 1080 x 1440 px atau 900 x 1200 px dengan rasio tegak 3:4 seperti cover novel atau cerpen.</div>
                                <div class="hint">Tips cepat: judul jangan terlalu mepet ke pinggir, pakai gambar yang tetap jelas saat dipotong kecil, dan simpan file di bawah 4 MB agar upload lebih lancar.</div>
                            </div>
                        </div>
                        <div class="creator-cover-card-preview">
                            <span class="section-kicker">Preview di Katalog</span>
                            <div class="creator-cover-card-shell">
                                <article class="work-card work-card-premium creator-cover-card-mockup">
                                    <div class="work-cover" id="creator-cover-card-cover">
                                        <span class="type-tag" id="creator-cover-card-type">Cerpen</span>
                                        <span class="free-tag" id="creator-cover-card-access">Gratis</span>
                                        <div class="cover-fade"></div>
                                        <div class="cover-meta">
                                            <span class="cover-pill">Preview katalog</span>
                                        </div>
                                    </div>
                                    <div class="work-body">
                                        <h3 id="creator-cover-card-title">Judul karya akan tampil di sini</h3>
                                        <div class="work-meta">Tampilan contoh di katalog Dayakarya</div>
                                        <div class="work-card-footer">
                                            <span class="read-link">Masuk ke karya</span>
                                            <span class="read-stat">Preview</span>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                        <div class="creator-cover-detail-preview">
                            <span class="section-kicker">Preview di Halaman Karya</span>
                            <div class="creator-cover-detail-shell">
                                <div class="work-hero creator-work-hero-mockup">
                                    <div class="work-hero-cover work-cover" id="creator-detail-cover">
                                        <span class="type-tag" id="creator-detail-type">Cerpen</span>
                                        <div class="cover-fade"></div>
                                        <div class="cover-meta">
                                            <span class="cover-pill">Preview hero karya</span>
                                        </div>
                                    </div>
                                    <div class="work-hero-copy">
                                        <span class="section-kicker">Fokus Karya</span>
                                        <h3 id="creator-detail-title">Judul karya akan tampil di sini</h3>
                                        <div class="work-meta work-meta-rich">
                                            <span>✍️ Nama kreator</span>
                                            <span>•</span>
                                            <span id="creator-detail-access">Gratis untuk pembaca</span>
                                        </div>
                                        <p class="work-synopsis" id="creator-detail-synopsis">Sinopsis karya akan tampil di sini supaya kreator bisa lihat apakah cover dan copy-nya terasa cocok saat masuk ke halaman detail.</p>
                                        <div class="work-badges">
                                            <span class="work-badge" id="creator-detail-badge">Mode baca yang lebih fokus</span>
                                            <span class="work-badge">Preview halaman karya</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="creator-title-guard" id="creator-title-guard">
                            <strong id="creator-title-guard-label">Judul masih aman</strong>
                            <p id="creator-title-guard-copy">Panjang judul saat ini masih nyaman untuk kartu katalog dan hero halaman karya.</p>
                        </div>
                        <div class="creator-title-guard" id="creator-synopsis-guard">
                            <strong id="creator-synopsis-guard-label">Sinopsis masih aman</strong>
                            <p id="creator-synopsis-guard-copy">Panjang sinopsis saat ini masih nyaman untuk hero halaman karya dan tidak terasa sesak.</p>
                        </div>
                    </div>
                </div>

                <div class="creator-part-panel" id="editor-section-parts">
                    <div class="creator-part-panel-head">
                        <div>
                            <span class="section-kicker">Kelanjutan Part</span>
                            <h3>Lanjutkan dari Part 1 ke part berikutnya tanpa ribet.</h3>
                            <p>Pilih part yang mau kamu edit, atau langsung buka part baru untuk lanjut cerita berikutnya.</p>
                        </div>
                        <button class="btn btn-ghost" id="editor-add-part" type="button" onclick="createNextPart()">Tambah Part Berikutnya</button>
                    </div>
                    <div class="creator-part-list" id="creator-part-list">
                        <p class="creator-part-empty">Part karya akan muncul di sini setelah draft dimuat.</p>
                    </div>
                </div>

                <div class="creator-writing-guide creator-writing-guide-compact field-text-content">
                    <div class="creator-writing-guide-head">
                        <strong>Panduan cepat biar enak dibaca</strong>
                        <span>Pegangan singkat ini cukup buat menjaga ritme tulisan tetap nyaman di layar HP.</span>
                    </div>
                    <div class="creator-writing-guide-grid">
                        <div class="creator-writing-tip">
                            <strong>1. Satu momen, satu paragraf</strong>
                            <span>Begitu ada aksi baru atau tokoh bicara, pindah paragraf supaya napas cerita tetap lega.</span>
                        </div>
                        <div class="creator-writing-tip">
                            <strong>2. Jaga pembuka tetap cepat</strong>
                            <span>Dua sampai empat paragraf pertama sebaiknya sudah bikin pembaca merasa ada sesuatu yang sedang berjalan.</span>
                        </div>
                        <div class="creator-writing-tip">
                            <strong>3. Dialog berdiri sendiri</strong>
                            <span>Kalau dialog dicampur paragraf panjang, pembaca lebih cepat lelah. Pisahkan supaya iramanya lebih enteng.</span>
                        </div>
                    </div>
                    <details class="creator-writing-example-shell">
                        <summary>Lihat contoh format paragraf yang ringan dibaca</summary>
                        <div class="creator-writing-example">
                            <span class="section-kicker">Contoh Format</span>
                            <pre id="writing-example">Hujan turun sejak magrib. Di teras rumah itu, Damar masih duduk sendirian.

Ia menatap jalan yang basah, seolah sedang menunggu sesuatu yang entah akan datang atau tidak.

"Ayah belum pulang?" tanya adiknya pelan.

Damar menoleh sebentar, lalu menggeleng.</pre>
                        </div>
                    </details>
                </div>

                <div class="creator-form-grid" id="editor-section-summary">
                    <div class="creator-form-divider" style="grid-column:1/-1">
                        <strong>Ringkasan karya</strong>
                        <span>Isi identitas karya dulu supaya arah cerpennya jelas sebelum lanjut ke part aktif.</span>
                    </div>
                    <div class="field">
                        <label>Judul karya</label>
                        <input id="editor-title" placeholder="Judul karya">
                    </div>
                    <div class="field">
                        <label>Tipe karya</label>
                        <select id="editor-type" onchange="toggleEditorFields()">
                            <option value="cerpen">Cerpen</option>
                            <option value="novel">Novel Berseri</option>
                            <option value="podcast">Podcast</option>
                            <option value="audio_story">Audio Story</option>
                            <option value="video_series">Video Series</option>
                            <option value="dongeng">Dongeng</option>
                            <option value="motivasi">Cerita Motivasi</option>
                            <option value="audiobook">Audiobook</option>
                        </select>
                    </div>
                    <div class="field" style="grid-column:1/-1">
                        <label>Sinopsis</label>
                        <textarea id="editor-synopsis" rows="4" placeholder="Ringkas isi karya kamu di sini."></textarea>
                    </div>
                    <div class="creator-form-divider" style="grid-column:1/-1">
                        <strong>Produksi part aktif</strong>
                        <span>Fokus utama penulis ada di sini: pilih part, tulis isi, lalu rapikan sampai enak dibaca.</span>
                    </div>
                    <div class="field" style="grid-column:1/-1">
                        <label id="editor-chapter-label">Judul bagian aktif</label>
                        <input id="editor-chapter-title" placeholder="Contoh: Ketika Hujan Datang">
                        <div class="hint" id="editor-active-part-copy">Pilih part yang ingin kamu lanjutkan, lalu isi judul dan kontennya di sini.</div>
                    </div>
                    <div class="field field-text-content" style="grid-column:1/-1" id="editor-section-content">
                        <label>Isi karya</label>
                        <div class="creator-inline-actions">
                            <button class="btn btn-ghost" type="button" onclick="autoFormatContent()">Rapikan Otomatis</button>
                            <span class="hint">Cocok untuk teks copy-paste yang masih numpuk jadi blok panjang.</span>
                        </div>
                        <textarea id="editor-content" rows="14" placeholder="Tulis isi cerpen atau bagian pembuka karya kamu di sini."></textarea>
                        <div class="hint">Pisahkan antar paragraf dengan satu baris kosong supaya hasil baca nanti tampil lebih rapi.</div>
                    </div>
                    <div class="creator-autoformat-compare field-text-content" id="creator-autoformat-compare" hidden>
                        <div class="creator-autoformat-head">
                            <div>
                                <strong>Perbandingan Sebelum dan Sesudah</strong>
                                <span>Lihat ringkasan perubahan hasil rapikan otomatis sebelum lanjut simpan.</span>
                            </div>
                            <button class="btn btn-ghost" type="button" id="creator-autoformat-restore" onclick="restoreAutoFormat()" hidden>Kembalikan Sebelum Dirapikan</button>
                        </div>
                        <div class="creator-autoformat-grid">
                            <div class="creator-autoformat-card">
                                <span class="section-kicker">Sebelum</span>
                                <div class="creator-autoformat-meta" id="creator-autoformat-before-meta">0 paragraf · 0 kata</div>
                                <pre id="creator-autoformat-before">Belum ada preview sebelum dirapikan.</pre>
                            </div>
                            <div class="creator-autoformat-card">
                                <span class="section-kicker">Sesudah</span>
                                <div class="creator-autoformat-meta" id="creator-autoformat-after-meta">0 paragraf · 0 kata</div>
                                <pre id="creator-autoformat-after">Belum ada preview sesudah dirapikan.</pre>
                            </div>
                        </div>
                    </div>
                    <div class="field field-audio-url" style="grid-column:1/-1" hidden>
                        <label>URL audio</label>
                        <input id="editor-audio-url" placeholder="https://...">
                    </div>
                    <div class="field field-video-url" style="grid-column:1/-1" hidden>
                        <label>URL video</label>
                        <input id="editor-video-url" placeholder="https://...">
                    </div>
                    <div class="creator-monetization-panel" style="grid-column:1/-1" id="editor-section-monetization">
                        <div class="creator-collapsible-head creator-collapsible-head-soft">
                            <div>
                                <span class="section-kicker">Monetisasi</span>
                                <strong>Akses dan harga part</strong>
                                <span id="creator-monetization-summary">Mode gratis aktif. Bagian ini bisa dibuka saat part sudah siap dijual.</span>
                            </div>
                            <button class="btn btn-ghost creator-collapsible-toggle" id="creator-monetization-toggle" type="button" aria-expanded="false" onclick="toggleEditorSection('monetization')">Buka Monetisasi</button>
                        </div>
                        <div class="creator-collapsible-body creator-monetization-body" id="creator-monetization-body" hidden>
                            <div class="creator-form-divider creator-form-divider-soft">
                                <strong>Akses dan harga</strong>
                                <span>Bagian ini sengaja dipisah supaya kamu bisa fokus menulis dulu. Atur premium hanya saat part memang sudah siap dijual.</span>
                            </div>
                            <div class="creator-monetization-grid">
                                <div class="field field-media-duration">
                                    <label>Durasi (detik)</label>
                                    <input id="editor-duration" type="number" min="0" placeholder="Contoh: 180">
                                </div>
                                <div class="field field-premium-toggle">
                                    <label>Akses</label>
                                    <select id="editor-is-premium">
                                        <option value="0">Gratis</option>
                                        <option value="1">Premium</option>
                                    </select>
                                </div>
                                <div class="field field-price-credit">
                                    <label>Harga credit</label>
                                    <input id="editor-price-credit" type="number" min="0" placeholder="0">
                                    <div class="hint" id="editor-price-hint">Kalau karya masih gratis, biarkan harga tetap 0 dulu biar fokus kamu tetap ke produksi naskah.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="creator-editor-actions">
                    <div class="creator-editor-actions-copy">
                        <strong>Simpan aman dulu, publish setelah checklist hijau.</strong>
                        <span>Auto-save tetap aktif, tapi simpan manual saat kamu selesai blok kerja penting.</span>
                    </div>
                    <button class="btn btn-gold" id="editor-save" onclick="saveDraft()">Simpan Draft</button>
                    <button class="btn btn-primary" id="editor-publish" type="button" onclick="publishWork()">Tayangkan Karya</button>
                    <a href="{{ route('creator.dashboard') }}" class="btn btn-ghost">Nanti Lanjut Lagi</a>
                </div>
                <div class="creator-autosave-status-wrap">
                    <div class="creator-autosave-status" id="creator-autosave-status">Semua perubahan sudah tersimpan.</div>
                    <div class="creator-autosave-meta" id="creator-autosave-meta">Belum ada riwayat simpan.</div>
                </div>
                <div class="creator-title-guard field-text-content" id="creator-content-guard">
                    <strong id="creator-content-guard-label">Isi part masih aman</strong>
                    <p id="creator-content-guard-copy">Paragraf dan ritme tulisan saat ini masih nyaman untuk dibaca di layar HP.</p>
                </div>
            </div>

            <aside class="creator-side">
                <div class="creator-side-card creator-side-card-soft field-text-content creator-side-card-preview" id="creator-preview-card">
                    <span class="section-kicker">Preview Baca</span>
                    <h3>Lihat dulu hasil baca sebelum tayang.</h3>
                    <p>Kalau preview terasa sesak, biasanya paragrafnya masih terlalu rapat atau kalimatnya terlalu panjang.</p>
                    <div class="creator-preview-meta">
                        <span id="preview-paragraph-count">0 paragraf</span>
                        <span id="preview-word-count">0 kata</span>
                    </div>
                    <div class="creator-reading-preview" id="creator-reading-preview">
                        <p>Tulis isi karya dulu, nanti preview baca akan tampil di sini.</p>
                    </div>
                </div>
                <div class="creator-side-card" id="creator-text-note">
                    <span class="section-kicker">Biar Enak Dibaca</span>
                    <h3>Buat pembuka yang cepat masuk dan paragraf yang tidak bikin capek.</h3>
                    <p>Kalau ini cerpen, isi bagian pertamanya harus sudah cukup kuat bikin orang mau lanjut.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft" id="creator-media-note">
                    <span class="section-kicker">Biar Enak Dinikmati</span>
                    <h3>Kalau audio atau video, pastikan file utamanya benar-benar siap diputar.</h3>
                    <p>Jangan sampai user sudah bayar tapi ketemu link media yang kosong atau pengalaman yang patah-patah.</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
  const workId = '{{ request()->route('work') }}';
  const debugEndpoint = 'http://127.0.0.1:7777/event';
  const COVER_MIN_WIDTH = 900;
  const COVER_MIN_HEIGHT = 1200;
  const COVER_RECOMMENDED_RATIO = 3 / 4;
  const COVER_MAX_BYTES = 4 * 1024 * 1024;
  const editorState = {
    chapters: [],
    activeChapterId: null,
    cover: null,
    previewObjectUrl: null,
    lastAutoFormatOriginal: null,
    autosaveTimer: null,
    isSaving: false,
    isLoaded: false,
    lastSavedSnapshot: '',
    lastSaveMode: 'manual',
    skipLeaveGuard: false,
    lastSavedAt: null,
    coverSectionOpen: null,
    monetizationSectionOpen: null,
  };

  // #region debug-point A:frontend-debug-report
  function reportDraftSaveDebug(hypothesisId, msg, data = {}) {
    fetch(debugEndpoint, {
      method: 'POST',
      body: JSON.stringify({
        sessionId: 'draft-save-error',
        runId: 'pre-fix',
        hypothesisId,
        location: 'resources/views/creator/editor.blade.php',
        msg: `[DEBUG] ${msg}`,
        data,
        ts: Date.now(),
      }),
    }).catch(() => {});
  }
  // #endregion

  function editorMode(type) {
    if (type === 'video_series') return 'video';
    if (['podcast', 'audio_story', 'dongeng', 'audiobook'].includes(type)) return 'audio';
    return 'text';
  }

  function toggleEditorFields() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);

    document.querySelectorAll('.field-text-content').forEach((item) => {
      item.hidden = mode !== 'text';
    });
    document.querySelector('.field-audio-url').hidden = mode !== 'audio';
    document.querySelector('.field-video-url').hidden = mode !== 'video';
    document.querySelector('.field-media-duration').hidden = mode === 'text';
    updateReadingPreview();
  }

  function syncAccessPricingState() {
    const accessField = document.querySelector('#editor-is-premium');
    const priceField = document.querySelector('#editor-price-credit');
    const priceHint = document.querySelector('#editor-price-hint');
    const summary = document.querySelector('#creator-monetization-summary');
    if (!accessField || !priceField || !priceHint) return;

    const isPremium = accessField.value === '1';
    priceField.disabled = !isPremium;

    if (!isPremium) {
      priceField.value = '0';
      priceHint.textContent = 'Mode gratis aktif. Harga dikunci di 0 supaya kamu bisa fokus ke produksi naskah dulu.';
      if (summary) {
        summary.textContent = 'Mode gratis aktif. Bagian ini bisa dibuka saat part sudah siap dijual.';
      }
      updateEditorCommandCenter();
      return;
    }

    priceHint.textContent = 'Mode premium aktif. Isi harga credit hanya saat part ini memang sudah siap dijual.';
    if (summary) {
      const price = Number(priceField.value || 0);
      summary.textContent = price > 0
        ? `Mode premium aktif dengan harga ${price} credit.`
        : 'Mode premium aktif. Lengkapi harga credit sebelum part dijual.';
    }
    updateEditorCommandCenter();
  }

  function setEditorSectionState(section, expanded) {
    const body = document.querySelector(`#creator-${section}-body`) || document.querySelector(`#creator-${section}-section-body`);
    const toggle = document.querySelector(`#creator-${section}-toggle`);
    if (!body || !toggle) return;

    body.hidden = !expanded;
    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');

    if (section === 'cover') {
      editorState.coverSectionOpen = expanded;
      toggle.textContent = expanded ? 'Tutup Finishing' : 'Buka Finishing';
      return;
    }

    if (section === 'monetization') {
      editorState.monetizationSectionOpen = expanded;
      toggle.textContent = expanded ? 'Tutup Monetisasi' : 'Buka Monetisasi';
    }
  }

  function toggleEditorSection(section) {
    if (section === 'cover') {
      setEditorSectionState(section, !editorState.coverSectionOpen);
      syncPublishChecklistVisibility();
      return;
    }

    if (section === 'monetization') {
      setEditorSectionState(section, !editorState.monetizationSectionOpen);
      syncPublishChecklistVisibility();
    }
  }

  function syncCoverSectionSummary() {
    const summary = document.querySelector('#creator-cover-section-summary');
    if (!summary) return;

    if (editorState.cover) {
      summary.textContent = 'Cover sudah ada. Buka bagian ini kalau kamu mau ganti gambar atau cek preview katalog.';
      return;
    }

    summary.textContent = 'Belum ada cover. Buka bagian ini saat kamu masuk tahap finishing.';
  }

  function syncEditorFocusSections(forceDefault = false) {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const isPremium = document.querySelector('#editor-is-premium')?.value === '1';

    if (forceDefault || editorState.coverSectionOpen === null) {
      editorState.coverSectionOpen = mode !== 'text';
    }

    if (forceDefault || editorState.monetizationSectionOpen === null) {
      editorState.monetizationSectionOpen = mode !== 'text' || isPremium;
    }

    if (isPremium && !editorState.monetizationSectionOpen) {
      editorState.monetizationSectionOpen = true;
    }

    setEditorSectionState('cover', Boolean(editorState.coverSectionOpen));
    setEditorSectionState('monetization', Boolean(editorState.monetizationSectionOpen));
    syncCoverSectionSummary();
    syncPublishChecklistVisibility();
  }

  function syncEditorModeNotes() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const textNote = document.querySelector('#creator-text-note');
    const mediaNote = document.querySelector('#creator-media-note');

    if (textNote) {
      textNote.hidden = mode !== 'text';
    }

    if (mediaNote) {
      mediaNote.hidden = mode === 'text';
    }

    updateEditorCommandCenter();
  }

  function shouldShowPublishChecklist() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const synopsis = document.querySelector('#editor-synopsis')?.value || '';
    const content = document.querySelector('#editor-content')?.value || '';
    const isPremium = document.querySelector('#editor-is-premium')?.value === '1';
    const synopsisWords = synopsis.trim().split(/\s+/).filter(Boolean).length;
    const contentWords = content.trim().split(/\s+/).filter(Boolean).length;

    if (editorState.coverSectionOpen || editorState.monetizationSectionOpen) {
      return true;
    }

    if (editorState.cover || isPremium) {
      return true;
    }

    if (synopsisWords >= 12) {
      return true;
    }

    if (mode === 'text') {
      return contentWords >= 60;
    }

    return false;
  }

  function syncPublishChecklistVisibility() {
    const checklist = document.querySelector('.creator-publish-checklist');
    const note = document.querySelector('#creator-publish-note');
    if (!checklist || !note) return;

    const show = shouldShowPublishChecklist();
    checklist.hidden = !show;
    note.hidden = show;
  }

  function escapePreviewHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function splitSentences(text) {
    const matches = text.match(/[^.!?]+[.!?]+(?:["”']+)?|[^.!?]+$/g);
    return (matches || [text])
      .map((item) => item.trim())
      .filter(Boolean);
  }

  function summarizeContent(text) {
    const clean = String(text || '').trim();
    if (!clean) {
      return {
        paragraphs: 0,
        words: 0,
      };
    }

    return {
      paragraphs: clean.split(/\n\s*\n/).map((item) => item.trim()).filter(Boolean).length,
      words: clean.split(/\s+/).filter(Boolean).length,
    };
  }

  function truncatePreviewText(text, maxLength = 360) {
    const clean = String(text || '').trim();
    if (!clean) return 'Belum ada isi untuk ditampilkan.';
    if (clean.length <= maxLength) return clean;
    return clean.slice(0, maxLength).trimEnd() + '...';
  }

  function renderAutoFormatComparison(beforeText, afterText) {
    const shell = document.querySelector('#creator-autoformat-compare');
    const beforeMeta = document.querySelector('#creator-autoformat-before-meta');
    const afterMeta = document.querySelector('#creator-autoformat-after-meta');
    const beforePreview = document.querySelector('#creator-autoformat-before');
    const afterPreview = document.querySelector('#creator-autoformat-after');
    const restoreButton = document.querySelector('#creator-autoformat-restore');

    if (!shell || !beforeMeta || !afterMeta || !beforePreview || !afterPreview) return;

    const beforeSummary = summarizeContent(beforeText);
    const afterSummary = summarizeContent(afterText);

    beforeMeta.textContent = `${beforeSummary.paragraphs} paragraf · ${beforeSummary.words} kata`;
    afterMeta.textContent = `${afterSummary.paragraphs} paragraf · ${afterSummary.words} kata`;
    beforePreview.textContent = truncatePreviewText(beforeText);
    afterPreview.textContent = truncatePreviewText(afterText);
    shell.hidden = false;
    if (restoreButton) {
      restoreButton.hidden = !editorState.lastAutoFormatOriginal;
    }
  }

  function normalizeParagraphText(text) {
    return text
      .replace(/\r\n?/g, '\n')
      .replace(/\t/g, ' ')
      .replace(/[ \u00A0]+/g, ' ')
      .replace(/\n{3,}/g, '\n\n')
      .trim();
  }

  function autoFormatStoryContent(raw) {
    let text = normalizeParagraphText(raw);
    if (!text) return '';

    const hasParagraphGap = /\n\s*\n/.test(text);
    let paragraphs = [];

    if (hasParagraphGap) {
      paragraphs = text
        .split(/\n\s*\n/)
        .map((paragraph) => paragraph.split('\n').map((line) => line.trim()).filter(Boolean).join(' '))
        .filter(Boolean);
    } else {
      const denseText = text.split('\n').map((line) => line.trim()).filter(Boolean).join(' ');
      const sentences = splitSentences(denseText);
      let buffer = [];
      let bufferWords = 0;

      sentences.forEach((sentence) => {
        const sentenceWords = sentence.split(/\s+/).filter(Boolean).length;
        const looksLikeDialogue = /["“”]/.test(sentence);

        if (looksLikeDialogue && buffer.length) {
          paragraphs.push(buffer.join(' ').trim());
          buffer = [];
          bufferWords = 0;
        }

        buffer.push(sentence);
        bufferWords += sentenceWords;

        if (looksLikeDialogue || bufferWords >= 55 || buffer.length >= 2) {
          paragraphs.push(buffer.join(' ').trim());
          buffer = [];
          bufferWords = 0;
        }
      });

      if (buffer.length) {
        paragraphs.push(buffer.join(' ').trim());
      }
    }

    return paragraphs
      .map((paragraph) => paragraph
        .replace(/\s*([,.!?;:])\s*/g, '$1 ')
        .replace(/\s{2,}/g, ' ')
        .trim())
      .filter(Boolean)
      .join('\n\n');
  }

  function updateReadingPreview() {
    const preview = document.querySelector('#creator-reading-preview');
    const paragraphCount = document.querySelector('#preview-paragraph-count');
    const wordCount = document.querySelector('#preview-word-count');
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    if (!preview || !paragraphCount || !wordCount) return;

    if (mode !== 'text') {
      preview.innerHTML = '<p>Preview baca khusus ditampilkan untuk karya teks seperti cerpen dan novel.</p>';
      paragraphCount.textContent = '0 paragraf';
      wordCount.textContent = '0 kata';
      return;
    }

    const content = document.querySelector('#editor-content')?.value || '';
    const clean = content.trim();
    if (!clean) {
      preview.innerHTML = '<p>Tulis isi karya dulu, nanti preview baca akan tampil di sini.</p>';
      paragraphCount.textContent = '0 paragraf';
      wordCount.textContent = '0 kata';
      return;
    }

    const paragraphs = clean.split(/\n\s*\n/).map((item) => item.trim()).filter(Boolean);
    const words = clean.split(/\s+/).filter(Boolean);

    paragraphCount.textContent = `${paragraphs.length} paragraf`;
    wordCount.textContent = `${words.length} kata`;
    preview.innerHTML = paragraphs
      .map((paragraph) => `<p>${escapePreviewHtml(paragraph).replace(/\n/g, '<br>')}</p>`)
      .join('');
  }

  function getCurrentChapterMeta(chapterId = editorState.activeChapterId) {
    return editorState.chapters.find((item) => Number(item.id) === Number(chapterId)) || null;
  }

  function updateEditorQuery(chapterId) {
    const url = new URL(window.location.href);
    if (chapterId) {
      url.searchParams.set('chapter_id', chapterId);
    } else {
      url.searchParams.delete('chapter_id');
    }

    window.history.replaceState({}, '', url);
  }

  function getDraftPayload() {
    return {
      title: document.querySelector('#editor-title').value,
      type: document.querySelector('#editor-type').value,
      synopsis: document.querySelector('#editor-synopsis').value,
      chapter_id: editorState.activeChapterId,
      chapter_title: document.querySelector('#editor-chapter-title').value,
      content: document.querySelector('#editor-content').value,
      audio_url: document.querySelector('#editor-audio-url').value,
      video_url: document.querySelector('#editor-video-url').value,
      duration_seconds: document.querySelector('#editor-duration').value ? Number(document.querySelector('#editor-duration').value) : null,
      is_premium: document.querySelector('#editor-is-premium').value === '1',
      price_credit: Number(document.querySelector('#editor-price-credit').value || 0),
    };
  }

  function getDraftSnapshot() {
    return JSON.stringify(getDraftPayload());
  }

  function setAutosaveStatus(message, state = 'idle') {
    const status = document.querySelector('#creator-autosave-status');
    if (!status) return;
    status.textContent = message;
    status.classList.remove('is-idle', 'is-pending', 'is-saving', 'is-error');
    status.classList.add(`is-${state}`);
    renderChapterList();
    renderPublishChecklist();
  }

  function formatSavedTime(value = null) {
    const date = value ? new Date(value) : new Date();
    return new Intl.DateTimeFormat('id-ID', {
      hour: '2-digit',
      minute: '2-digit',
      timeZone: 'Asia/Jakarta',
    }).format(date) + ' WIB';
  }

  function setAutosaveMeta(message) {
    const meta = document.querySelector('#creator-autosave-meta');
    if (!meta) return;
    meta.textContent = message;
  }

  function markLastSaved(value = null, source = 'save') {
    editorState.lastSavedAt = value || new Date().toISOString();
    const sourceLabel = source === 'publish' ? 'Terakhir ditayangkan' : 'Terakhir tersimpan';
    setAutosaveMeta(`${sourceLabel} ${formatSavedTime(editorState.lastSavedAt)}.`);
  }

  function hasUnsavedDraftChanges() {
    if (!editorState.isLoaded) return false;
    if (editorState.isSaving) return true;
    return getDraftSnapshot() !== editorState.lastSavedSnapshot;
  }

  function shouldBlockEditorLeave() {
    if (editorState.skipLeaveGuard) return false;
    return hasUnsavedDraftChanges();
  }

  function clearAutosaveTimer() {
    if (editorState.autosaveTimer) {
      window.clearTimeout(editorState.autosaveTimer);
      editorState.autosaveTimer = null;
    }
  }

  function markDraftDirty() {
    if (!editorState.isLoaded) return;
    setAutosaveStatus('Perubahan belum disimpan. Auto-save akan jalan sebentar lagi.', 'pending');
    if (editorState.lastSavedAt) {
      setAutosaveMeta(`Draft lokal berubah. Simpan aman terakhir ${formatSavedTime(editorState.lastSavedAt)}.`);
      return;
    }
    setAutosaveMeta('Draft lokal berubah. Belum ada simpan aman di sesi ini.');
    renderChapterList();
  }

  function queueAutoSave() {
    if (!editorState.isLoaded || editorState.isSaving) return;

    const snapshot = getDraftSnapshot();
    if (snapshot === editorState.lastSavedSnapshot) {
      setAutosaveStatus('Semua perubahan sudah tersimpan.', 'idle');
      return;
    }

    clearAutosaveTimer();
    markDraftDirty();
    editorState.autosaveTimer = window.setTimeout(() => {
      saveDraft({ silent: true, auto: true });
    }, 3000);
  }

  function getPartDraftState(chapterId) {
    const activeId = Number(editorState.activeChapterId);
    const currentId = Number(chapterId);

    if (currentId === activeId && editorState.isSaving) {
      return {
        label: 'Sedang disimpan',
        className: 'is-saving',
      };
    }

    if (currentId === activeId && hasUnsavedDraftChanges()) {
      return {
        label: 'Belum aman',
        className: 'is-pending',
      };
    }

    return {
      label: 'Aman tersimpan',
      className: 'is-safe',
    };
  }

  function getPartProgress(chapter) {
    const isActive = Number(chapter?.id) === Number(editorState.activeChapterId);
    const title = isActive
      ? (document.querySelector('#editor-chapter-title')?.value || '')
      : (chapter?.title || '');
    const content = isActive
      ? (document.querySelector('#editor-content')?.value || '')
      : (chapter?.content || '');
    const isPremium = isActive
      ? document.querySelector('#editor-is-premium')?.value === '1'
      : Boolean(chapter?.is_premium);
    const priceCredit = isActive
      ? Number(document.querySelector('#editor-price-credit')?.value || 0)
      : Number(chapter?.price_credit || 0);

    const titleReady = Boolean(String(title).trim());
    const contentReady = String(content).trim().split(/\s+/).filter(Boolean).length >= 20;
    const accessReady = !isPremium || priceCredit > 0;

    return [
      {
        label: titleReady ? 'Judul siap' : 'Judul belum',
        className: titleReady ? 'is-ready' : 'is-empty',
      },
      {
        label: contentReady ? 'Isi siap' : 'Isi belum',
        className: contentReady ? 'is-ready' : 'is-empty',
      },
      {
        label: accessReady ? 'Akses siap' : 'Harga belum',
        className: accessReady ? 'is-ready' : 'is-empty',
      },
    ];
  }

  function getPublishChecklist() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const synopsis = document.querySelector('#editor-synopsis')?.value || '';
    const chapterTitle = document.querySelector('#editor-chapter-title')?.value || '';
    const content = document.querySelector('#editor-content')?.value || '';
    const audioUrl = document.querySelector('#editor-audio-url')?.value || '';
    const videoUrl = document.querySelector('#editor-video-url')?.value || '';
    const duration = Number(document.querySelector('#editor-duration')?.value || 0);
    const isPremium = document.querySelector('#editor-is-premium')?.value === '1';
    const priceCredit = Number(document.querySelector('#editor-price-credit')?.value || 0);
    const synopsisWords = synopsis.trim().split(/\s+/).filter(Boolean).length;
    const contentWords = content.trim().split(/\s+/).filter(Boolean).length;

    const items = [
      {
        label: editorState.cover ? 'Cover siap' : 'Cover belum ada',
        ok: Boolean(editorState.cover),
      },
      {
        label: synopsisWords >= 12 ? 'Sinopsis siap' : 'Sinopsis masih tipis',
        ok: synopsisWords >= 12,
      },
      {
        label: String(chapterTitle).trim() ? 'Judul part siap' : 'Judul part belum ada',
        ok: Boolean(String(chapterTitle).trim()),
      },
    ];

    if (mode === 'text') {
      items.push({
        label: contentWords >= 60 ? 'Isi part siap' : 'Isi part masih pendek',
        ok: contentWords >= 60,
      });
    } else if (mode === 'audio') {
      items.push({
        label: audioUrl.trim() ? 'Audio siap' : 'URL audio belum ada',
        ok: Boolean(audioUrl.trim()),
      });
      items.push({
        label: duration > 0 ? 'Durasi siap' : 'Durasi belum diisi',
        ok: duration > 0,
      });
    } else {
      items.push({
        label: videoUrl.trim() ? 'Video siap' : 'URL video belum ada',
        ok: Boolean(videoUrl.trim()),
      });
      items.push({
        label: duration > 0 ? 'Durasi siap' : 'Durasi belum diisi',
        ok: duration > 0,
      });
    }

    items.push({
      label: !isPremium || priceCredit > 0 ? 'Akses siap' : 'Harga premium belum ada',
      ok: !isPremium || priceCredit > 0,
    });

    return items;
  }

  function renderPublishChecklist() {
    const container = document.querySelector('#creator-publish-checklist');
    const summary = document.querySelector('#creator-publish-summary');
    const button = document.querySelector('#editor-publish');
    if (!container || !summary) return;

    const items = getPublishChecklist();
    const readyCount = items.filter((item) => item.ok).length;
    const missingItems = items.filter((item) => !item.ok);

    container.innerHTML = items
      .map((item) => `<span class="creator-publish-item ${item.ok ? 'is-ready' : 'is-pending'}">${item.label}</span>`)
      .join('');

    if (!missingItems.length) {
      summary.textContent = 'Semua poin penting sudah siap. Karya bisa ditayangkan kapan saja.';
      button?.removeAttribute('data-readiness');
      syncPublishChecklistVisibility();
      updateEditorCommandCenter();
      return;
    }

    summary.textContent = `${readyCount}/${items.length} poin sudah siap. Lengkapi dulu yang masih kurang sebelum tayang.`;
    button?.setAttribute('data-readiness', 'needs-review');
    syncPublishChecklistVisibility();
    updateEditorCommandCenter();
  }

  function updateEditorCommandCenter() {
    const label = document.querySelector('#creator-editor-status-label');
    const copy = document.querySelector('#creator-editor-status-copy');
    const modePill = document.querySelector('#creator-editor-mode-pill');
    const partPill = document.querySelector('#creator-editor-part-pill');
    const accessPill = document.querySelector('#creator-editor-access-pill');

    if (!label || !copy || !modePill || !partPill || !accessPill) {
      return;
    }

    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const checklist = getPublishChecklist();
    const missingItems = checklist.filter((item) => !item.ok);
    const activeChapter = getCurrentChapterMeta();
    const chapterNumber = activeChapter?.order || 1;
    const accessLabel = document.querySelector('#editor-is-premium')?.value === '1' ? 'Premium' : 'Gratis';

    modePill.textContent = `Mode ${DK.typeLabel(type)}`;
    partPill.textContent = `Part ${chapterNumber} aktif`;
    accessPill.textContent = `Akses ${accessLabel}`;

    if (!editorState.isLoaded) {
      label.textContent = 'Memuat prioritas draft…';
      copy.textContent = 'Begitu data draft masuk, editor ini akan menandai langkah paling penting yang perlu kamu kerjakan.';
      return;
    }

    if (!missingItems.length) {
      label.textContent = 'Draft ini sudah siap tayang.';
      copy.textContent = 'Checklist sudah hijau. Kamu bisa publish sekarang atau cek sekali lagi preview dan cover sebelum tayang.';
      return;
    }

    if (missingItems.length <= 2) {
      label.textContent = 'Draft ini tinggal sedikit lagi.';
      copy.textContent = `Yang masih perlu dibereskan: ${missingItems.map((item) => item.label.toLowerCase()).join(', ')}.`;
      return;
    }

    if (mode === 'text') {
      label.textContent = 'Fokus dulu ke isi part aktif.';
      copy.textContent = 'Begitu judul part, isi, dan sinopsis cukup kuat, barulah finishing dan monetisasi terasa lebih ringan dikerjakan.';
      return;
    }

    label.textContent = 'Pastikan media utama benar-benar siap.';
    copy.textContent = 'Untuk audio atau video, prioritas terbesarnya adalah link media, durasi, dan akses part sebelum publish.';
  }

  async function attemptLeaveEditor(targetUrl) {
    if (!targetUrl) return;

    if (!editorState.isLoaded) {
      editorState.skipLeaveGuard = true;
      window.location.href = targetUrl;
      return;
    }

    if (editorState.isSaving) {
      const confirmed = window.confirm('Perubahan masih sedang disimpan. Kalau keluar sekarang, update terakhir bisa hilang. Tetap keluar?');
      if (confirmed) {
        editorState.skipLeaveGuard = true;
        window.location.href = targetUrl;
      }
      return;
    }

    if (!hasUnsavedDraftChanges()) {
      editorState.skipLeaveGuard = true;
      window.location.href = targetUrl;
      return;
    }

    setAutosaveStatus('Menyimpan perubahan sebelum keluar dari editor...', 'saving');
    const saved = await saveDraft({ silent: true, auto: true });
    if (saved) {
      editorState.skipLeaveGuard = true;
      window.location.href = targetUrl;
      return;
    }

    const confirmed = window.confirm('Perubahan terakhir belum berhasil disimpan. Tetap keluar dari editor?');
    if (confirmed) {
      editorState.skipLeaveGuard = true;
      window.location.href = targetUrl;
    }
  }

  function updateActivePartHint() {
    const label = document.querySelector('#editor-chapter-label');
    const hint = document.querySelector('#editor-active-part-copy');
    const activeChapter = getCurrentChapterMeta();
    const chapterNumber = activeChapter?.order || 1;

    if (label) {
      label.textContent = `Judul Part ${chapterNumber}`;
    }

    if (hint) {
      hint.textContent = `Kamu sedang mengerjakan Part ${chapterNumber}. Simpan dulu sebelum pindah ke part lain.`;
    }

    updateEditorCommandCenter();
  }

  function clearPreviewObjectUrl() {
    if (editorState.previewObjectUrl) {
      URL.revokeObjectURL(editorState.previewObjectUrl);
      editorState.previewObjectUrl = null;
    }
  }

  function refreshCoverCardPreview() {
    const cardCover = document.querySelector('#creator-cover-card-cover');
    const cardTitle = document.querySelector('#creator-cover-card-title');
    const cardType = document.querySelector('#creator-cover-card-type');
    const cardAccess = document.querySelector('#creator-cover-card-access');

    if (!cardCover || !cardTitle || !cardType || !cardAccess) return;

    const title = document.querySelector('#editor-title')?.value?.trim() || 'Judul karya akan tampil di sini';
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const isPremium = document.querySelector('#editor-is-premium')?.value === '1';

    cardTitle.textContent = title;
    cardType.textContent = DK.typeLabel(type);
    cardAccess.textContent = isPremium ? 'Premium' : 'Gratis';

    if (editorState.cover) {
      cardCover.style.backgroundImage = `url("${editorState.cover.replaceAll('"', '&quot;')}")`;
      cardCover.style.backgroundSize = 'cover';
      cardCover.style.backgroundPosition = 'center';
    } else {
      cardCover.style.backgroundImage = '';
      cardCover.style.backgroundSize = '';
      cardCover.style.backgroundPosition = '';
    }
  }

  function detailModeBadge(type) {
    if (type === 'video_series') return 'Mode nonton yang lebih fokus';
    if (['podcast', 'audio_story', 'dongeng', 'audiobook'].includes(type)) return 'Mode dengar yang lebih fokus';
    return 'Mode baca yang lebih fokus';
  }

  function refreshCoverDetailPreview() {
    const detailCover = document.querySelector('#creator-detail-cover');
    const detailType = document.querySelector('#creator-detail-type');
    const detailTitle = document.querySelector('#creator-detail-title');
    const detailSynopsis = document.querySelector('#creator-detail-synopsis');
    const detailAccess = document.querySelector('#creator-detail-access');
    const detailBadge = document.querySelector('#creator-detail-badge');

    if (!detailCover || !detailType || !detailTitle || !detailSynopsis || !detailAccess || !detailBadge) return;

    const title = document.querySelector('#editor-title')?.value?.trim() || 'Judul karya akan tampil di sini';
    const synopsis = document.querySelector('#editor-synopsis')?.value?.trim() || 'Sinopsis karya akan tampil di sini supaya kreator bisa lihat apakah cover dan copy-nya terasa cocok saat masuk ke halaman detail.';
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const isPremium = document.querySelector('#editor-is-premium')?.value === '1';

    detailType.textContent = DK.typeLabel(type);
    detailTitle.textContent = title;
    detailSynopsis.textContent = synopsis;
    detailAccess.textContent = isPremium ? 'Butuh credit untuk membuka part premium' : 'Gratis untuk pembaca';
    detailBadge.textContent = detailModeBadge(type);

    if (editorState.cover) {
      detailCover.style.backgroundImage = `url("${editorState.cover.replaceAll('"', '&quot;')}")`;
      detailCover.style.backgroundSize = 'cover';
      detailCover.style.backgroundPosition = 'center';
    } else {
      detailCover.style.backgroundImage = '';
      detailCover.style.backgroundSize = '';
      detailCover.style.backgroundPosition = '';
    }
  }

  function updateTitleGuard() {
    const guard = document.querySelector('#creator-title-guard');
    const label = document.querySelector('#creator-title-guard-label');
    const copy = document.querySelector('#creator-title-guard-copy');
    const rawTitle = document.querySelector('#editor-title')?.value || '';

    if (!guard || !label || !copy) return;

    const title = rawTitle.trim();
    const length = title.length;

    guard.classList.remove('is-safe', 'is-watch', 'is-warning');

    if (!length) {
      guard.classList.add('is-safe');
      label.textContent = 'Judul masih kosong';
      copy.textContent = 'Isi judul dulu, nanti sistem bantu cek apakah panjangnya masih aman untuk katalog dan halaman karya.';
      return;
    }

    if (length <= 45) {
      guard.classList.add('is-safe');
      label.textContent = 'Judul masih aman';
      copy.textContent = `Panjang judul ${length} karakter. Ini masih nyaman untuk kartu katalog dan hero halaman karya.`;
      return;
    }

    if (length <= 65) {
      guard.classList.add('is-watch');
      label.textContent = 'Judul mulai mepet';
      copy.textContent = `Panjang judul ${length} karakter. Masih bisa dipakai, tapi cek lagi preview katalog dan halaman karya supaya tidak terasa kepanjangan.`;
      return;
    }

    guard.classList.add('is-warning');
    label.textContent = 'Judul terlalu panjang';
    copy.textContent = `Panjang judul ${length} karakter. Sebaiknya dipendekkan supaya tidak rawan kepotong atau terasa berat saat tampil di katalog dan halaman karya.`;
  }

  function updateSynopsisGuard() {
    const guard = document.querySelector('#creator-synopsis-guard');
    const label = document.querySelector('#creator-synopsis-guard-label');
    const copy = document.querySelector('#creator-synopsis-guard-copy');
    const rawSynopsis = document.querySelector('#editor-synopsis')?.value || '';

    if (!guard || !label || !copy) return;

    const synopsis = rawSynopsis.trim();
    const length = synopsis.length;

    guard.classList.remove('is-safe', 'is-watch', 'is-warning');

    if (!length) {
      guard.classList.add('is-safe');
      label.textContent = 'Sinopsis masih kosong';
      copy.textContent = 'Isi sinopsis dulu, nanti sistem bantu cek apakah panjangnya masih enak dibaca saat tampil di halaman karya.';
      return;
    }

    if (length <= 160) {
      guard.classList.add('is-safe');
      label.textContent = 'Sinopsis masih aman';
      copy.textContent = `Panjang sinopsis ${length} karakter. Ini masih nyaman untuk hero halaman karya dan tetap terasa ringan dibaca.`;
      return;
    }

    if (length <= 260) {
      guard.classList.add('is-watch');
      label.textContent = 'Sinopsis mulai panjang';
      copy.textContent = `Panjang sinopsis ${length} karakter. Masih bisa dipakai, tapi cek preview halaman karya supaya tidak terasa padat.`;
      return;
    }

    guard.classList.add('is-warning');
    label.textContent = 'Sinopsis terlalu panjang';
    copy.textContent = `Panjang sinopsis ${length} karakter. Sebaiknya diringkas supaya pembaca cepat paham tanpa merasa sesak saat membuka halaman karya.`;
  }

  function updateContentGuard() {
    const guard = document.querySelector('#creator-content-guard');
    const label = document.querySelector('#creator-content-guard-label');
    const copy = document.querySelector('#creator-content-guard-copy');
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const rawContent = document.querySelector('#editor-content')?.value || '';

    if (!guard || !label || !copy) return;

    if (mode !== 'text') {
      guard.classList.remove('is-safe', 'is-watch', 'is-warning');
      guard.classList.add('is-safe');
      label.textContent = 'Guard isi fokus untuk karya teks';
      copy.textContent = 'Untuk audio dan video, fokus utama tetap ke file media. Guard paragraf dipakai khusus untuk cerpen dan novel.';
      return;
    }

    const clean = rawContent.trim();
    guard.classList.remove('is-safe', 'is-watch', 'is-warning');

    if (!clean) {
      guard.classList.add('is-safe');
      label.textContent = 'Isi part masih kosong';
      copy.textContent = 'Mulai isi part dulu, nanti sistem bantu cek apakah paragrafnya sudah enak dibaca atau masih terlalu padat.';
      return;
    }

    const paragraphs = clean.split(/\n\s*\n/).map((item) => item.trim()).filter(Boolean);
    const wordsPerParagraph = paragraphs.map((paragraph) => paragraph.split(/\s+/).filter(Boolean).length);
    const longestParagraph = wordsPerParagraph.length ? Math.max(...wordsPerParagraph) : 0;
    const shortOpening = paragraphs.slice(0, 2).join(' ').split(/\s+/).filter(Boolean).length;
    const denseParagraphCount = wordsPerParagraph.filter((count) => count >= 90).length;

    if (denseParagraphCount >= 2 || longestParagraph >= 130) {
      guard.classList.add('is-warning');
      label.textContent = 'Isi part terlalu padat';
      copy.textContent = `Paragraf terpanjang sekitar ${longestParagraph} kata. Pecah lagi jadi paragraf lebih pendek supaya pembaca tidak cepat lelah.`;
      return;
    }

    if (denseParagraphCount >= 1 || longestParagraph >= 75 || shortOpening < 18) {
      guard.classList.add('is-watch');
      if (shortOpening < 18) {
        label.textContent = 'Pembuka masih terlalu tipis';
        copy.textContent = `Dua paragraf awal baru sekitar ${shortOpening} kata. Tambah sedikit konteks atau ketegangan supaya pembuka terasa lebih kuat.`;
        return;
      }

      label.textContent = 'Isi part mulai rapat';
      copy.textContent = `Paragraf terpanjang sekitar ${longestParagraph} kata. Masih bisa dipakai, tapi akan lebih enak kalau beberapa bagian dipecah lagi.`;
      return;
    }

    guard.classList.add('is-safe');
    label.textContent = 'Isi part masih aman';
    copy.textContent = `Ada ${paragraphs.length} paragraf dan paragraf terpanjang sekitar ${longestParagraph} kata. Ritme bacanya sudah cukup nyaman di layar HP.`;
  }

  function autoFormatContent() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);
    const contentField = document.querySelector('#editor-content');
    const msg = document.querySelector('#editor-msg');

    if (mode !== 'text') {
      msg.innerHTML = '<div class="alert alert-error">Rapikan otomatis saat ini difokuskan untuk karya teks seperti cerpen dan novel.</div>';
      return;
    }

    if (!contentField) return;

    const raw = contentField.value || '';
    if (!raw.trim()) {
      msg.innerHTML = '<div class="alert alert-error">Isi part masih kosong. Tempel dulu naskah yang mau dirapikan.</div>';
      return;
    }

    const formatted = autoFormatStoryContent(raw);
    if (!formatted) {
      msg.innerHTML = '<div class="alert alert-error">Naskah belum berhasil dirapikan. Coba cek isi teksnya dulu.</div>';
      return;
    }

    editorState.lastAutoFormatOriginal = raw;
    contentField.value = formatted;
    renderAutoFormatComparison(raw, formatted);
    updateReadingPreview();
    updateContentGuard();
    renderPublishChecklist();
    msg.innerHTML = '<div class="alert alert-success">Teks berhasil dirapikan otomatis. Cek lagi hasilnya, lalu edit sedikit kalau ada bagian yang masih ingin kamu atur manual.</div>';
  }

  function restoreAutoFormat() {
    const contentField = document.querySelector('#editor-content');
    const msg = document.querySelector('#editor-msg');

    if (!contentField || !editorState.lastAutoFormatOriginal) {
      msg.innerHTML = '<div class="alert alert-error">Belum ada hasil rapikan otomatis yang bisa dikembalikan.</div>';
      return;
    }

    const currentValue = contentField.value || '';
    contentField.value = editorState.lastAutoFormatOriginal;
    renderAutoFormatComparison(currentValue, editorState.lastAutoFormatOriginal);
    editorState.lastAutoFormatOriginal = null;
    const restoreButton = document.querySelector('#creator-autoformat-restore');
    if (restoreButton) {
      restoreButton.hidden = true;
    }
    updateReadingPreview();
    updateContentGuard();
    renderPublishChecklist();
    msg.innerHTML = '<div class="alert alert-success">Isi part sudah dikembalikan ke versi sebelum dirapikan otomatis.</div>';
  }

  function syncCoverPreview(coverUrl, options = {}) {
    const image = document.querySelector('#creator-cover-preview');
    const empty = document.querySelector('#creator-cover-empty');
    const { temporary = false } = options;

    if (!temporary) {
      clearPreviewObjectUrl();
      editorState.cover = coverUrl || null;
    }

    if (!image || !empty) return;

    if (coverUrl) {
      image.src = coverUrl;
      image.hidden = false;
      empty.hidden = true;
      refreshCoverCardPreview();
      refreshCoverDetailPreview();
      syncCoverSectionSummary();
      updateTitleGuard();
      updateSynopsisGuard();
      updateContentGuard();
      renderPublishChecklist();
      return;
    }

    image.removeAttribute('src');
    image.hidden = true;
    empty.hidden = false;
    refreshCoverCardPreview();
    refreshCoverDetailPreview();
    syncCoverSectionSummary();
    updateTitleGuard();
    updateSynopsisGuard();
    updateContentGuard();
    renderPublishChecklist();
    updateEditorCommandCenter();
  }

  function formatFileSize(bytes) {
    if (bytes >= 1024 * 1024) {
      return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }

    return `${Math.round(bytes / 1024)} KB`;
  }

  function readImageMeta(file) {
    return new Promise((resolve, reject) => {
      const objectUrl = URL.createObjectURL(file);
      const image = new Image();

      image.onload = () => {
        resolve({
          width: image.naturalWidth,
          height: image.naturalHeight,
          ratio: image.naturalWidth / image.naturalHeight,
          objectUrl,
        });
      };

      image.onerror = () => {
        URL.revokeObjectURL(objectUrl);
        reject(new Error('File cover tidak bisa dibaca sebagai gambar.'));
      };

      image.src = objectUrl;
    });
  }

  async function inspectSelectedCover() {
    const input = document.querySelector('#editor-cover-file');
    const msg = document.querySelector('#editor-msg');
    const file = input?.files?.[0];
    if (!file) return true;

    if (file.size > COVER_MAX_BYTES) {
      msg.innerHTML = `<div class="alert alert-error">Ukuran file cover ${formatFileSize(file.size)}. Maksimal upload 4 MB supaya prosesnya tetap lancar.</div>`;
      input.value = '';
      return false;
    }

    try {
      const meta = await readImageMeta(file);
      const ratioGap = Math.abs(meta.ratio - COVER_RECOMMENDED_RATIO);

      if (meta.width < COVER_MIN_WIDTH || meta.height < COVER_MIN_HEIGHT) {
        URL.revokeObjectURL(meta.objectUrl);
        msg.innerHTML = `<div class="alert alert-error">Ukuran cover masih terlalu kecil: ${meta.width} x ${meta.height}px. Minimal pakai ${COVER_MIN_WIDTH} x ${COVER_MIN_HEIGHT}px.</div>`;
        input.value = '';
        return false;
      }

      clearPreviewObjectUrl();
      editorState.previewObjectUrl = meta.objectUrl;
      syncCoverPreview(meta.objectUrl, { temporary: true });

      if (ratioGap > 0.03) {
        msg.innerHTML = `<div class="alert alert-error">Cover terbaca ${meta.width} x ${meta.height}px. Masih bisa dipakai, tapi rasionya kurang pas untuk template 3:4 dan hasilnya bisa terlihat kepotong.</div>`;
        return true;
      }

      msg.innerHTML = `<div class="alert alert-success">Cover siap diupload: ${meta.width} x ${meta.height}px, ${formatFileSize(file.size)}, rasio sudah pas untuk template 3:4.</div>`;
      return true;
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">File cover tidak bisa dibaca. Coba pilih JPG, PNG, atau WEBP yang valid.</div>';
      input.value = '';
      return false;
    }
  }

  function renderChapterList() {
    const container = document.querySelector('#creator-part-list');
    if (!container) return;

    if (!editorState.chapters.length) {
      container.innerHTML = '<p class="creator-part-empty">Part karya akan muncul di sini setelah draft dimuat.</p>';
      return;
    }

    container.innerHTML = editorState.chapters.map((chapter) => {
      const isActive = Number(chapter.id) === Number(editorState.activeChapterId);
      const accessLabel = chapter.is_premium
        ? `Premium ${Number(chapter.price_credit || 0)} credit`
        : 'Gratis';
      const statusLabel = chapter.status === 'published' ? 'Tayang' : 'Draft';
      const draftState = getPartDraftState(chapter.id);
      const progressItems = getPartProgress(chapter)
        .map((item) => `<span class="creator-part-progress-chip ${item.className}">${item.label}</span>`)
        .join('');

      return `
        <button
          type="button"
          class="creator-part-chip ${isActive ? 'is-active' : ''}"
          data-chapter-id="${chapter.id}"
        >
          <span class="creator-part-chip-topline">
            <span class="creator-part-chip-order">Part ${chapter.order}</span>
            <span class="creator-part-chip-state ${draftState.className}">${draftState.label}</span>
          </span>
          <strong>${escapePreviewHtml(chapter.title || `Bagian ${chapter.order}`)}</strong>
          <span class="creator-part-chip-meta">${statusLabel} · ${accessLabel}</span>
          <span class="creator-part-progress">${progressItems}</span>
        </button>
      `;
    }).join('');

    container.querySelectorAll('[data-chapter-id]').forEach((button) => {
      button.addEventListener('click', () => {
        switchEditorChapter(Number(button.dataset.chapterId));
      });
    });
  }

  function applyEditorPayload(work = {}, editor = {}, chapters = []) {
    if (Array.isArray(chapters)) {
      editorState.chapters = chapters;
    }

    editorState.activeChapterId = editor.chapter_id || editorState.activeChapterId;
    const incomingCover = work.cover_url ?? work.cover;
    const nextCover = Object.prototype.hasOwnProperty.call(work, 'cover')
      || Object.prototype.hasOwnProperty.call(work, 'cover_url')
      ? (incomingCover || null)
      : (editorState.cover || null);
    syncCoverPreview(nextCover);

    document.querySelector('#editor-heading').textContent = work.title || 'Lanjut Edit Draft';
    document.querySelector('#editor-title').value = work.title || '';
    document.querySelector('#editor-type').value = work.type || 'cerpen';
    document.querySelector('#editor-synopsis').value = work.synopsis || '';
    document.querySelector('#editor-chapter-title').value = editor.chapter_title || 'Bagian 1';
    document.querySelector('#editor-content').value = editor.content || '';
    document.querySelector('#editor-audio-url').value = editor.audio_url || '';
    document.querySelector('#editor-video-url').value = editor.video_url || '';
    document.querySelector('#editor-duration').value = editor.duration_seconds || '';
    document.querySelector('#editor-is-premium').value = editor.is_premium ? '1' : '0';
    document.querySelector('#editor-price-credit').value = editor.price_credit || 0;

    updateEditorQuery(editorState.activeChapterId);
    renderChapterList();
    updateActivePartHint();
    toggleEditorFields();
    syncAccessPricingState();
    syncEditorFocusSections();
    syncEditorModeNotes();
    updateReadingPreview();
    refreshCoverCardPreview();
    refreshCoverDetailPreview();
    updateTitleGuard();
    updateSynopsisGuard();
    updateContentGuard();
    editorState.lastSavedSnapshot = getDraftSnapshot();
    editorState.isLoaded = true;
    setAutosaveStatus('Semua perubahan sudah tersimpan.', 'idle');
    markLastSaved(editor.updated_at || work.updated_at || new Date().toISOString(), 'save');
    renderPublishChecklist();
  }

  async function uploadWorkCover() {
    const input = document.querySelector('#editor-cover-file');
    const button = document.querySelector('#editor-upload-cover');
    const msg = document.querySelector('#editor-msg');
    const file = input?.files?.[0];

    if (!file) {
      msg.innerHTML = '<div class="alert alert-error">Pilih file cover dulu sebelum upload.</div>';
      return;
    }

    const validSelection = await inspectSelectedCover();
    if (!validSelection) {
      return;
    }

    button.disabled = true;
    msg.innerHTML = '';

    try {
      const formData = new FormData();
      formData.append('cover', file);

      const headers = { Accept: 'application/json' };
      if (DK.token()) {
        headers.Authorization = 'Bearer ' + DK.token();
      }

      const response = await fetch(DK.api + '/creator/works/' + workId + '/cover', {
        method: 'POST',
        headers,
        body: formData,
      });

      const rawText = await response.text();
      const contentType = response.headers.get('content-type') || '';
      const data = contentType.includes('application/json')
        ? JSON.parse(rawText || '{}')
        : { message: rawText || 'Server tidak mengirim JSON.' };

      if (!response.ok) {
        const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Cover belum berhasil diunggah.');
        msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
        return;
      }

      syncCoverPreview(data.cover || data.work?.cover || null);
      msg.innerHTML = '<div class="alert alert-success">Cover karya berhasil diunggah dan siap dipakai di halaman karya.</div>';
      input.value = '';
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">Upload cover belum berhasil. Coba lagi sebentar.</div>';
    } finally {
      button.disabled = false;
    }
  }

  async function switchEditorChapter(chapterId) {
    if (!chapterId || Number(chapterId) === Number(editorState.activeChapterId)) {
      return;
    }

    if (getDraftSnapshot() !== editorState.lastSavedSnapshot) {
      const saved = await saveDraft({ silent: true, auto: true });
      if (!saved) {
        return;
      }
    }

    await loadDraftEditor(chapterId);
  }

  async function ensureEditorSession() {
    if (!DK.token()) {
      location.href = '/masuk';
      return false;
    }

    const me = await DK.get('/auth/me');
    if (!me?.user?.id) {
      DK.clearToken();
      location.href = '/masuk';
      return false;
    }

    return true;
  }

  async function loadDraftEditor(chapterId = null) {
    const okSession = await ensureEditorSession();
    if (!okSession) return;

    const msg = document.querySelector('#editor-msg');

    try {
      const params = new URLSearchParams(window.location.search);
      if (chapterId) {
        params.set('chapter_id', chapterId);
      }

      const query = params.toString();
      const data = await DK.get('/creator/works/' + workId + (query ? `?${query}` : ''));
      applyEditorPayload(data.work || {}, data.editor || {}, data.chapters || []);
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">Draft belum berhasil dimuat. Coba masuk ulang lalu buka lagi dari dashboard.</div>';
    }
  }

  async function createNextPart() {
    const button = document.querySelector('#editor-add-part');
    const msg = document.querySelector('#editor-msg');
    if (!button) return;

    button.disabled = true;
    msg.innerHTML = '';

    try {
      const response = await fetch(DK.api + '/creator/works/' + workId + '/chapters', {
        method: 'POST',
        headers: DK.headers(),
        body: JSON.stringify({}),
      });

      const rawText = await response.text();
      const contentType = response.headers.get('content-type') || '';
      const data = contentType.includes('application/json')
        ? JSON.parse(rawText || '{}')
        : { message: rawText || 'Server tidak mengirim JSON.' };

      if (!response.ok) {
        const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Part baru belum berhasil dibuat.');
        msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
        return;
      }

      applyEditorPayload({
        title: document.querySelector('#editor-title').value,
        type: document.querySelector('#editor-type').value,
        synopsis: document.querySelector('#editor-synopsis').value,
      }, data.editor || {}, data.chapters || []);
      msg.innerHTML = '<div class="alert alert-success">Part baru sudah siap. Lanjutkan isi cerita berikutnya di bawah ini.</div>';
      document.querySelector('#editor-chapter-title')?.focus();
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">Part baru belum berhasil dibuat. Coba lagi sebentar.</div>';
    } finally {
      button.disabled = false;
    }
  }

  async function saveDraft(options = {}) {
    const { silent = false, auto = false } = options;
    const button = document.querySelector('#editor-save');
    const msg = document.querySelector('#editor-msg');
    const snapshotBeforeSave = getDraftSnapshot();

    if (editorState.isSaving) {
      return false;
    }

    if (snapshotBeforeSave === editorState.lastSavedSnapshot) {
      setAutosaveStatus('Semua perubahan sudah tersimpan.', 'idle');
      return true;
    }

    clearAutosaveTimer();
    editorState.isSaving = true;
    editorState.lastSaveMode = auto ? 'auto' : 'manual';
    button.disabled = true;
    if (!silent) {
      msg.innerHTML = '';
    }
    setAutosaveStatus(auto ? 'Menyimpan otomatis...' : 'Menyimpan perubahan...', 'saving');

    const payload = JSON.parse(snapshotBeforeSave);

    let ok = false;
    let data = {};
    // #region debug-point B:save-request-start
    reportDraftSaveDebug('B', 'save draft request started', {
      workId,
      type: payload.type,
      titleLength: payload.title?.length ?? 0,
      synopsisLength: payload.synopsis?.length ?? 0,
      contentLength: payload.content?.length ?? 0,
      hasAudioUrl: Boolean(payload.audio_url),
      hasVideoUrl: Boolean(payload.video_url),
      isPremium: payload.is_premium,
      priceCredit: payload.price_credit,
    });
    // #endregion

    try {
      const response = await fetch(DK.api + '/creator/works/' + workId + '/save', {
        method: 'POST',
        headers: DK.headers(),
        body: JSON.stringify(payload),
      });
      const rawText = await response.text();
      const contentType = response.headers.get('content-type') || '';

      // #region debug-point C:save-response
      reportDraftSaveDebug('C', 'save draft response received', {
        status: response.status,
        ok: response.ok,
        contentType,
        responseSnippet: rawText.slice(0, 500),
      });
      // #endregion

      ok = response.ok;
      data = contentType.includes('application/json')
        ? JSON.parse(rawText || '{}')
        : { message: rawText || 'Server tidak mengirim JSON.' };
    } catch (error) {
      // #region debug-point D:save-request-error
      reportDraftSaveDebug('D', 'save draft request crashed before parsing response', {
        name: error?.name,
        message: error?.message,
      });
      // #endregion
      data = { message: 'Server Error saat menyimpan draft.' };
    }

    button.disabled = false;
    editorState.isSaving = false;

    if (!ok) {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Draft belum berhasil disimpan.');
      if (!silent) {
        msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
      }
      setAutosaveStatus(auto ? 'Auto-save gagal. Coba simpan manual sebentar lagi.' : 'Simpan gagal. Coba lagi sebentar.', 'error');
      return false;
    }

    applyEditorPayload({
      title: data.work?.title || payload.title,
      type: data.work?.type || payload.type,
      synopsis: data.work?.synopsis || payload.synopsis,
    }, data.editor || {
      chapter_id: payload.chapter_id,
      chapter_title: payload.chapter_title,
      content: payload.content,
      audio_url: payload.audio_url,
      video_url: payload.video_url,
      duration_seconds: payload.duration_seconds,
      is_premium: payload.is_premium,
      price_credit: payload.price_credit,
    }, data.chapters || editorState.chapters);
    editorState.lastSavedSnapshot = getDraftSnapshot();
    setAutosaveStatus(auto ? 'Perubahan terbaru sudah tersimpan otomatis.' : 'Semua perubahan sudah tersimpan.', 'idle');
    markLastSaved(data.editor?.updated_at || data.work?.updated_at || new Date().toISOString(), 'save');
    if (!silent) {
      msg.innerHTML = '<div class="alert alert-success">Draft berhasil disimpan. Kamu bisa lanjut lagi kapan saja dari halaman ini.</div>';
    }
    return true;
  }

  async function publishWork() {
    const button = document.querySelector('#editor-publish');
    const msg = document.querySelector('#editor-msg');
    if (!button) return;

    const checklist = getPublishChecklist();
    const missingItems = checklist.filter((item) => !item.ok);
    if (missingItems.length) {
      renderPublishChecklist();
      msg.innerHTML = `<div class="alert alert-error">Karya belum siap tayang. Lengkapi dulu: ${missingItems.map((item) => item.label.toLowerCase()).join(', ')}.</div>`;
      return;
    }

    button.disabled = true;
    msg.innerHTML = '';

    try {
      const response = await fetch(DK.api + '/creator/works/' + workId + '/publish', {
        method: 'POST',
        headers: DK.headers(),
        body: JSON.stringify({
          chapter_id: editorState.activeChapterId,
        }),
      });

      const rawText = await response.text();
      const contentType = response.headers.get('content-type') || '';
      const data = contentType.includes('application/json')
        ? JSON.parse(rawText || '{}')
        : { message: rawText || 'Server tidak mengirim JSON.' };

      if (!response.ok) {
        const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Karya belum berhasil ditayangkan.');
        msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
        return;
      }

      applyEditorPayload({
        title: data.work?.title || document.querySelector('#editor-title').value,
        type: data.work?.type || document.querySelector('#editor-type').value,
        synopsis: data.work?.synopsis || document.querySelector('#editor-synopsis').value,
        cover: data.work?.cover || editorState.cover,
      }, data.editor || {}, data.chapters || editorState.chapters);
      markLastSaved(data.work?.published_at || data.editor?.published_at || new Date().toISOString(), 'publish');
      msg.innerHTML = '<div class="alert alert-success">Karya sudah tayang. Sekarang akun lain seharusnya bisa melihatnya di katalog karya.</div>';
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">Karya belum berhasil ditayangkan. Coba lagi sebentar.</div>';
    } finally {
      button.disabled = false;
    }
  }

  document.querySelector('#editor-cover-file')?.addEventListener('change', inspectSelectedCover);
  document.querySelector('#editor-title')?.addEventListener('input', refreshCoverCardPreview);
  document.querySelector('#editor-title')?.addEventListener('input', refreshCoverDetailPreview);
  document.querySelector('#editor-title')?.addEventListener('input', updateTitleGuard);
  document.querySelector('#editor-title')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-title')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-type')?.addEventListener('change', refreshCoverCardPreview);
  document.querySelector('#editor-type')?.addEventListener('change', refreshCoverDetailPreview);
  document.querySelector('#editor-type')?.addEventListener('change', syncEditorFocusSections);
  document.querySelector('#editor-type')?.addEventListener('change', syncEditorModeNotes);
  document.querySelector('#editor-type')?.addEventListener('change', renderPublishChecklist);
  document.querySelector('#editor-type')?.addEventListener('change', queueAutoSave);
  document.querySelector('#editor-is-premium')?.addEventListener('change', refreshCoverCardPreview);
  document.querySelector('#editor-is-premium')?.addEventListener('change', refreshCoverDetailPreview);
  document.querySelector('#editor-is-premium')?.addEventListener('change', syncAccessPricingState);
  document.querySelector('#editor-is-premium')?.addEventListener('change', syncEditorFocusSections);
  document.querySelector('#editor-is-premium')?.addEventListener('change', renderPublishChecklist);
  document.querySelector('#editor-is-premium')?.addEventListener('change', queueAutoSave);
  document.querySelector('#editor-synopsis')?.addEventListener('input', refreshCoverDetailPreview);
  document.querySelector('#editor-synopsis')?.addEventListener('input', updateSynopsisGuard);
  document.querySelector('#editor-synopsis')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-synopsis')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-content')?.addEventListener('input', updateReadingPreview);
  document.querySelector('#editor-content')?.addEventListener('input', updateContentGuard);
  document.querySelector('#editor-content')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-content')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-type')?.addEventListener('change', updateReadingPreview);
  document.querySelector('#editor-type')?.addEventListener('change', updateContentGuard);
  document.querySelector('#editor-chapter-title')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-chapter-title')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-audio-url')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-audio-url')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-video-url')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-video-url')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-duration')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-duration')?.addEventListener('input', queueAutoSave);
  document.querySelector('#editor-price-credit')?.addEventListener('input', renderPublishChecklist);
  document.querySelector('#editor-price-credit')?.addEventListener('input', syncAccessPricingState);
  document.querySelector('#editor-price-credit')?.addEventListener('input', queueAutoSave);

  window.addEventListener('beforeunload', (event) => {
    if (!shouldBlockEditorLeave()) {
      return;
    }

    event.preventDefault();
    event.returnValue = '';
  });

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');
    if (!link) return;
    if (event.defaultPrevented) return;
    if (event.button !== 0) return;
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (link.target === '_blank' || link.hasAttribute('download')) return;

    const href = link.getAttribute('href') || '';
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
      return;
    }

    if (!shouldBlockEditorLeave()) {
      return;
    }

    const url = new URL(link.href, window.location.href);
    if (url.href === window.location.href) {
      return;
    }

    event.preventDefault();
    attemptLeaveEditor(url.href);
  }, true);

  loadDraftEditor();
</script>
@endpush
