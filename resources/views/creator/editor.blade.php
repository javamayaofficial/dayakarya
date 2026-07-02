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
                        <label>Judul bagian pertama</label>
                        <input id="editor-chapter-title" placeholder="Contoh: Ketika Hujan Datang">
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

  function editorMode(type) {
    if (type === 'video_series') return 'video';
    if (['podcast', 'audio_story', 'dongeng', 'audiobook'].includes(type)) return 'audio';
    return 'text';
  }

  function toggleEditorFields() {
    const type = document.querySelector('#editor-type')?.value || 'cerpen';
    const mode = editorMode(type);

    document.querySelector('.field-text-content').hidden = mode !== 'text';
    document.querySelector('.field-audio-url').hidden = mode !== 'audio';
    document.querySelector('.field-video-url').hidden = mode !== 'video';
    document.querySelector('.field-media-duration').hidden = mode === 'text';
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

  async function loadDraftEditor() {
    const okSession = await ensureEditorSession();
    if (!okSession) return;

    const msg = document.querySelector('#editor-msg');

    try {
      const data = await DK.get('/creator/works/' + workId);
      const work = data.work || {};
      const editor = data.editor || {};

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

      toggleEditorFields();
    } catch (error) {
      msg.innerHTML = '<div class="alert alert-error">Draft belum berhasil dimuat. Coba masuk ulang lalu buka lagi dari dashboard.</div>';
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

    try {
      const result = await DK.post('/creator/works/' + workId + '/save', payload);
      ok = result.ok;
      data = result.data;
    } catch (error) {
      data = { message: 'Server Error saat menyimpan draft.' };
    }

    button.disabled = false;

    if (!ok) {
      const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Draft belum berhasil disimpan.');
      msg.innerHTML = `<div class="alert alert-error">${first}</div>`;
      return;
    }

    document.querySelector('#editor-heading').textContent = payload.title || 'Lanjut Edit Draft';
    msg.innerHTML = '<div class="alert alert-success">Draft berhasil disimpan. Kamu bisa lanjut lagi kapan saja dari halaman ini.</div>';
  }

  loadDraftEditor();
</script>
@endpush
