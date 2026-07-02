@extends('layouts.app')
@section('title', 'Lanjut Edit Draft — Dayakarya')
@section('body_class', 'page-creator')

@section('content')
<section class="section">
    <div class="container creator-container">
        <div class="creator-hero">
            <div class="creator-hero-copy">
                <span class="section-kicker">Editor Draft</span>
                <h1>Lanjutkan karya yang tadi sempat kamu simpan.</h1>
                <p>Di sini kamu bisa terus rapikan judul, sinopsis, dan isi karya tanpa harus mulai dari awal lagi.</p>
                <div class="creator-hero-actions">
                    <a href="{{ route('creator.dashboard') }}" class="btn btn-ghost">Kembali ke Dashboard</a>
                </div>
            </div>
            <div class="creator-hero-note">
                <span class="mini-label">Fokus Produksi</span>
                <h2>Simpan perubahan kapan pun kamu butuh.</h2>
                <p>Untuk sekarang editor ini difokuskan agar draft cerpen dan karya dasar bisa lanjut dikerjakan dengan nyaman.</p>
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

                <div class="creator-part-panel">
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

                <div class="creator-writing-guide field-text-content">
                    <div class="creator-writing-guide-head">
                        <strong>Format menulis yang enak dibaca</strong>
                        <span>Ikuti pola ini supaya cerpen tidak terasa padat dan melelahkan.</span>
                    </div>
                    <div class="creator-writing-guide-grid">
                        <div class="creator-writing-tip">
                            <strong>1. Satu aksi, satu paragraf</strong>
                            <span>Kalau ada perpindahan suasana, tokoh bicara, atau aksi baru, pindah paragraf.</span>
                        </div>
                        <div class="creator-writing-tip">
                            <strong>2. Kasih jeda napas</strong>
                            <span>Gunakan satu baris kosong antar paragraf. Jangan numpuk jadi satu blok panjang.</span>
                        </div>
                        <div class="creator-writing-tip">
                            <strong>3. Pembuka jangan muter</strong>
                            <span>Usahakan 2-4 paragraf awal sudah bikin pembaca merasa ada sesuatu yang sedang terjadi.</span>
                        </div>
                        <div class="creator-writing-tip">
                            <strong>4. Dialog dipisah</strong>
                            <span>Kalau tokoh bicara, lebih enak dibaca kalau dialog berdiri sendiri, tidak dicampur paragraf panjang.</span>
                        </div>
                    </div>
                    <div class="creator-writing-example">
                        <span class="section-kicker">Contoh Format</span>
                        <pre id="writing-example">Hujan turun sejak magrib. Di teras rumah itu, Damar masih duduk sendirian.

Ia menatap jalan yang basah, seolah sedang menunggu sesuatu yang entah akan datang atau tidak.

"Ayah belum pulang?" tanya adiknya pelan.

Damar menoleh sebentar, lalu menggeleng.</pre>
                    </div>
                </div>

                <div class="creator-form-grid">
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
                    <div class="field" style="grid-column:1/-1">
                        <label id="editor-chapter-label">Judul bagian aktif</label>
                        <input id="editor-chapter-title" placeholder="Contoh: Ketika Hujan Datang">
                        <div class="hint" id="editor-active-part-copy">Pilih part yang ingin kamu lanjutkan, lalu isi judul dan kontennya di sini.</div>
                    </div>
                    <div class="field field-text-content" style="grid-column:1/-1">
                        <label>Isi karya</label>
                        <textarea id="editor-content" rows="14" placeholder="Tulis isi cerpen atau bagian pembuka karya kamu di sini."></textarea>
                        <div class="hint">Pisahkan antar paragraf dengan satu baris kosong supaya hasil baca nanti tampil lebih rapi.</div>
                    </div>
                    <div class="field field-audio-url" style="grid-column:1/-1" hidden>
                        <label>URL audio</label>
                        <input id="editor-audio-url" placeholder="https://...">
                    </div>
                    <div class="field field-video-url" style="grid-column:1/-1" hidden>
                        <label>URL video</label>
                        <input id="editor-video-url" placeholder="https://...">
                    </div>
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
                    </div>
                </div>

                <div class="creator-editor-actions">
                    <button class="btn btn-gold" id="editor-save" onclick="saveDraft()">Simpan Perubahan</button>
                    <a href="{{ route('creator.dashboard') }}" class="btn btn-ghost">Nanti Lanjut Lagi</a>
                </div>
            </div>

            <aside class="creator-side">
                <div class="creator-side-card creator-side-card-soft field-text-content">
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
                <div class="creator-side-card">
                    <span class="section-kicker">Biar Enak Dibaca</span>
                    <h3>Buat pembuka yang cepat masuk dan paragraf yang tidak bikin capek.</h3>
                    <p>Kalau ini cerpen, isi bagian pertamanya harus sudah cukup kuat bikin orang mau lanjut.</p>
                </div>
                <div class="creator-side-card creator-side-card-soft">
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
  const editorState = {
    chapters: [],
    activeChapterId: null,
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

  function escapePreviewHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
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

      return `
        <button
          type="button"
          class="creator-part-chip ${isActive ? 'is-active' : ''}"
          data-chapter-id="${chapter.id}"
        >
          <span class="creator-part-chip-order">Part ${chapter.order}</span>
          <strong>${escapePreviewHtml(chapter.title || `Bagian ${chapter.order}`)}</strong>
          <span class="creator-part-chip-meta">${statusLabel} · ${accessLabel}</span>
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
    updateReadingPreview();
  }

  async function switchEditorChapter(chapterId) {
    if (!chapterId || Number(chapterId) === Number(editorState.activeChapterId)) {
      return;
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

  async function saveDraft() {
    const button = document.querySelector('#editor-save');
    const msg = document.querySelector('#editor-msg');
    button.disabled = true;
    msg.innerHTML = '';

    const payload = {
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

    if (!ok) {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Draft belum berhasil disimpan.');
      msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
      return;
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
    msg.innerHTML = '<div class="alert alert-success">Draft berhasil disimpan. Kamu bisa lanjut lagi kapan saja dari halaman ini.</div>';
  }

  document.querySelector('#editor-content')?.addEventListener('input', updateReadingPreview);
  document.querySelector('#editor-type')?.addEventListener('change', updateReadingPreview);

  loadDraftEditor();
</script>
@endpush
