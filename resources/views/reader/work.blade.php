@extends('layouts.app')
@section('title', $work->title . ' — Dayakarya')
@section('desc', Str::limit($work->synopsis, 150))
@section('body_class', 'page-work')

@section('content')
@php
    $chapters = $work->chapters;
    $chapterCount = $chapters->count();
    $isAudioWork = $work->isAudio();
    $isVideoWork = $work->isVideo();
    $selectedStatusLabel = $selectedChapter?->is_premium ? 'Perlu dibuka dengan Credit' : 'Bisa langsung dinikmati';
    $chapterPayloads = $chapters->mapWithKeys(function ($chapter) use ($isAudioWork, $isVideoWork) {
        return [
            $chapter->id => [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'order' => $chapter->order,
                'is_premium' => (bool) $chapter->is_premium,
                'price_credit' => (int) $chapter->price_credit,
                'duration_seconds' => $chapter->duration_seconds,
                'content' => $chapter->is_premium || $isAudioWork || $isVideoWork ? null : $chapter->content,
                'audio_url' => $chapter->audio_url,
                'video_url' => $chapter->video_url,
            ],
        ];
    });
@endphp
<section class="section">
    <div class="container">
        <div class="work-hero card">
            <div class="work-hero-cover work-cover" style="{{ $work->cover ? "background-image:url('".$work->cover."');background-size:cover" : '' }}">
                <span class="type-tag">{{ config('dayakarya.work_types')[$work->type] ?? $work->type }}</span>
            </div>
            <div class="work-hero-copy">
                <span class="section-kicker">Fokus Karya</span>
                <h1>{{ $work->title }}</h1>
                <div class="work-meta work-meta-rich">
                    <span>✍️ {{ $work->creator->name }}</span>
                    <span>•</span>
                    <span>{{ number_format($work->views) }}x dibaca</span>
                    <span>•</span>
                    <span>{{ $work->chapters->where('status','published')->count() }} bagian</span>
                </div>
                <p class="work-synopsis">{{ $work->synopsis }}</p>
                <div class="work-hero-actions">
                    <button class="btn btn-ghost" onclick="DK.follow({{ $work->creator_id }})">+ Ikuti Kreator</button>
                    <a href="#fokus-karya" class="btn btn-primary">{{ $isVideoWork ? 'Mulai Nonton' : ($isAudioWork ? 'Mulai Dengar' : 'Mulai Nikmati') }}</a>
                </div>
                <div class="work-badges">
                    <span class="work-badge">{{ $isVideoWork ? 'Mode nonton yang lebih fokus' : ($isAudioWork ? 'Mode dengar yang lebih fokus' : 'Mode baca yang lebih fokus') }}</span>
                    <span class="work-badge">Pilih bagian, lalu nikmati tanpa terdistraksi</span>
                </div>
            </div>
        </div>

        <div class="section work-context">
            <div class="work-context-grid">
                <div class="context-card">
                    <span class="mini-label mini-label-dark">Cara Menikmati</span>
                    <h2>{{ $isVideoWork ? 'Pilih episode yang ingin ditonton, lalu biarkan layar ini fokus ke video yang sedang kamu pilih.' : ($isAudioWork ? 'Pilih episode yang ingin didengar, lalu biarkan fokusmu tetap di karya ini.' : 'Pilih bagian yang ingin dibaca, lalu nikmati isinya dengan tampilan yang lebih tenang.') }}</h2>
                    <p>{{ $isVideoWork ? 'Urutan episode, status akses, dan player video disusun supaya penonton tidak bingung pindah-pindah.' : ($isAudioWork ? 'Urutan episode, status akses, dan player disusun supaya pendengar tidak bingung pindah-pindah.' : 'Urutan bagian, status akses, dan ruang baca disusun supaya pembaca tidak terdistraksi dari isi cerita.') }}</p>
                </div>
                <div class="context-card context-card-soft">
                    <span class="mini-label mini-label-dark">Status Karya</span>
                    <h3>{{ $chapterCount }} bagian siap dinikmati, dari pembuka gratis sampai bagian premium.</h3>
                    <p>Kalau ada bagian yang terkunci, kamu bisa buka saat itu juga lalu langsung lanjut menikmati karya yang sedang dipilih.</p>
                </div>
            </div>
        </div>

        @if($selectedChapter)
            <div class="work-comfort-strip">
                <div class="comfort-pill">Akses dibuka tetap di tempat yang sama</div>
                <div class="comfort-pill">Pindah bagian gratis tanpa reload berulang</div>
                <div class="comfort-pill">Fokus tetap ke karya yang sedang kamu pilih</div>
            </div>
            <div class="work-focus-shell" id="fokus-karya">
                <aside class="work-playlist card">
                    <div class="section-head section-head-premium">
                        <div>
                            <span class="section-kicker">Daftar Bagian</span>
                            <h2>{{ $isVideoWork ? 'Pilih episode yang ingin ditonton' : ($isAudioWork ? 'Pilih episode yang ingin didengar' : 'Pilih bagian yang ingin dibaca') }}</h2>
                        </div>
                    </div>
                    <div class="work-playlist-list">
                        @foreach($chapters as $ch)
                            <div class="chapter-row {{ $selectedChapter->id === $ch->id ? 'is-active' : '' }}">
                                <div class="chapter-row-main">
                                    <span class="idx">{{ sprintf('%02d', $ch->order) }}</span>
                                    <div>
                                        <div class="chapter-row-title">{{ $ch->title }}</div>
                                        <div class="chapter-row-meta">
                                            @if($ch->is_premium)
                                                <span class="lock">🔒 {{ $ch->price_credit }} Credit</span>
                                            @else
                                                <span class="free">Gratis</span>
                                            @endif
                                            @if(($isAudioWork || $isVideoWork) && $ch->duration_seconds)
                                                <span>{{ gmdate('i:s', (int) $ch->duration_seconds) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($ch->is_premium)
                                    <button
                                        type="button"
                                        class="btn btn-ghost chapter-open-btn"
                                        data-chapter-id="{{ $ch->id }}"
                                        onclick="selectChapterById({{ $ch->id }})"
                                    >Lihat</button>
                                @else
                                    <button
                                        type="button"
                                        class="btn btn-ghost chapter-open-btn"
                                        data-chapter-id="{{ $ch->id }}"
                                        onclick="selectChapterById({{ $ch->id }})"
                                    >{{ $isVideoWork ? 'Tonton' : ($isAudioWork ? 'Dengar' : 'Baca') }}</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </aside>

                <div class="work-reader card" data-mode="{{ $isVideoWork ? 'video' : ($isAudioWork ? 'audio' : 'text') }}">
                    <div class="work-reader-head">
                        <div>
                            <span class="section-kicker">Sedang Dipilih</span>
                            <h2 id="chapter-focus-title">{{ $selectedChapter->title }}</h2>
                        </div>
                        <div class="work-reader-status" id="chapter-focus-meta">
                            <span>Bagian {{ sprintf('%02d', $selectedChapter->order) }}</span>
                            <span>{{ $selectedStatusLabel }}</span>
                        </div>
                    </div>

                    <div class="work-reader-tools">
                        <div class="work-reader-note">
                            {{ $isVideoWork ? 'Tontonan dibuat tetap fokus ke episode yang dipilih, tanpa muter-muter halaman lagi.' : ($isAudioWork ? 'Pendengar langsung tetap di panel yang sama saat pindah episode atau buka akses.' : 'Pembaca bisa atur ukuran baca supaya lebih nyaman di mata dan tetap fokus ke isi.') }}
                        </div>
                        @if(! $isAudioWork && ! $isVideoWork)
                            <div class="reading-controls" id="reading-controls">
                                <button type="button" class="is-active" data-reading-size="normal">A</button>
                                <button type="button" data-reading-size="large">A+</button>
                                <button type="button" data-reading-size="xlarge">A++</button>
                            </div>
                        @endif
                    </div>

                    <div id="chapter-focus-feedback"></div>

                    <div class="work-access-loading" id="chapter-access-loading" hidden>
                        <span class="work-access-loading-dot" aria-hidden="true"></span>
                        <div>
                            <strong>Sedang menyiapkan akses bagian ini...</strong>
                            <p id="chapter-access-loading-copy">Sebentar ya, kami cek dulu apakah bagian premium ini sudah pernah kamu buka.</p>
                        </div>
                    </div>

                    <div
                        class="work-locked-state"
                        id="chapter-lock-state"
                        @if(! $selectedChapter->is_premium) hidden @endif
                    >
                        <span class="mini-label mini-label-dark" id="chapter-lock-kicker">Butuh Akses</span>
                        <h3 id="chapter-lock-title">Bagian ini bisa langsung kamu buka saat siap lanjut.</h3>
                        <p id="chapter-lock-copy">Setelah dibuka, {{ $isVideoWork ? 'video' : ($isAudioWork ? 'audio' : 'isi karya') }} akan langsung tampil di sini supaya kamu tetap fokus ke bagian yang sedang dipilih.</p>
                        <button
                            type="button"
                            class="btn btn-gold"
                            id="chapter-focus-unlock"
                            data-chapter-id="{{ $selectedChapter->id }}"
                            data-action="unlock"
                            onclick="handleChapterPrimaryAction(this)"
                        >Buka Bagian Ini · {{ $selectedChapter->price_credit }} Credit</button>
                        <p class="work-soft-note" id="chapter-lock-note">Kalau credit belum cukup, kamu bisa isi saldo lalu kembali ke bagian ini tanpa kehilangan posisi.</p>
                    </div>

                    <div
                        class="work-audio-player"
                        id="chapter-audio-shell"
                        @if(! $isAudioWork || $selectedChapter->is_premium) hidden @endif
                    >
                        @if($selectedChapter->audio_url)
                            <audio id="chapter-audio" controls preload="metadata" src="{{ $selectedChapter->audio_url }}"></audio>
                        @else
                            <div class="work-soft-note">Audio untuk bagian ini belum tersedia.</div>
                        @endif
                    </div>

                    <div
                        class="work-video-player"
                        id="chapter-video-shell"
                        @if(! $isVideoWork || $selectedChapter->is_premium) hidden @endif
                    >
                        @if($selectedChapter->video_url)
                            <video id="chapter-video" controls playsinline preload="metadata" src="{{ $selectedChapter->video_url }}"></video>
                        @else
                            <div class="work-soft-note">Video untuk episode ini belum tersedia.</div>
                        @endif
                    </div>

                    <div
                        class="work-reader-output"
                        id="chapter-text-output"
                        @if($isAudioWork || $isVideoWork || $selectedChapter->is_premium) hidden @endif
                    >{!! nl2br(e($selectedChapter->content ?: 'Isi bagian ini belum tersedia.')) !!}</div>

                    <div class="work-reader-footer">
                        <span>{{ $isVideoWork ? 'Kalau sudah selesai, kamu bisa lanjut ke episode berikutnya dari daftar di samping.' : ($isAudioWork ? 'Kalau sudah selesai, kamu bisa pindah ke episode berikutnya dari daftar di samping.' : 'Kalau sudah selesai, kamu bisa lanjut ke bagian berikutnya dari daftar di samping.') }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="state" style="grid-column:1/-1">
                    <div class="emoji">🖋️</div>
                    <h3>Karya ini belum punya bagian yang tayang</h3>
                    <p>Begitu ada bagian yang terbit, pembaca dan pendengar bisa langsung menikmati dari halaman ini.</p>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
  DK.refreshCredit();
  const WORK_ID = @json($work->id);
  const chapterMap = @json($chapterPayloads, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

  function escapeWorkHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function nl2Paragraphs(value) {
    return escapeWorkHtml(value)
      .split(/\n{2,}/)
      .map((paragraph) => `<p>${paragraph.replace(/\n/g, '<br>')}</p>`)
      .join('');
  }

  function getCurrentSelectedChapterId() {
    const activeButton = document.querySelector('.chapter-row.is-active [data-chapter-id]');
    return Number(activeButton?.dataset.chapterId || @json($selectedChapter?->id) || 0);
  }

  function setActiveChapter(chapterId) {
    document.querySelectorAll('.chapter-row').forEach((row) => {
      const button = row.querySelector('[data-chapter-id]');
      row.classList.toggle('is-active', button && Number(button.dataset.chapterId) === Number(chapterId));
    });
  }

  function formatDuration(seconds) {
    if (!seconds) return '';
    const total = Number(seconds);
    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const secs = total % 60;
    if (hours > 0) {
      return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
  }

  function getChapterReturnUrl(chapterId) {
    const url = new URL(window.location.href);
    url.searchParams.set('bagian', chapterId);
    url.hash = 'fokus-karya';
    return `${url.pathname}${url.search}${url.hash}`;
  }

  function syncLockState(chapter, mode = 'default') {
    const unlockButton = document.querySelector('#chapter-focus-unlock');
    const lockTitle = document.querySelector('#chapter-lock-title');
    const lockCopy = document.querySelector('#chapter-lock-copy');
    const lockNote = document.querySelector('#chapter-lock-note');
    const isAuthenticated = Boolean(DK.token());

    if (!unlockButton) return;

    let action = 'unlock';
    let label = `Buka Bagian Ini · ${chapter.price_credit} Credit`;
    let title = 'Bagian ini bisa langsung kamu buka saat siap lanjut.';
    let copy = 'Setelah dibuka, isi karya akan langsung tampil di sini supaya kamu tetap fokus ke bagian yang sedang dipilih.';
    let note = `Bagian ini butuh ${chapter.price_credit} Credit. Kalau saldo belum cukup, kamu bisa isi credit dulu lalu kembali ke bagian yang sama.`;

    if (!isAuthenticated) {
      action = 'login';
      label = 'Masuk Untuk Lanjut';
      title = 'Bagian premium ini siap dibuka setelah kamu masuk.';
      copy = 'Masuk dulu, lalu kamu akan kembali ke bagian ini tanpa kehilangan posisi baca.';
      note = 'Setelah masuk, kamu bisa langsung lanjut buka bagian premium ini dengan credit dari akun yang sama.';
    } else if (mode === 'needs_topup') {
      action = 'topup';
      label = 'Isi Credit Sekarang';
      title = 'Credit kamu belum cukup untuk bagian ini.';
      copy = 'Top up dulu, nanti kamu balik lagi ke bagian yang sama supaya tidak perlu cari part-nya lagi.';
      note = `Bagian ini butuh ${chapter.price_credit} Credit. Setelah saldo masuk, cukup lanjutkan lagi dari bagian yang sama.`;
    } else if (mode === 'already_unlocked') {
      action = 'refresh';
      label = 'Muat Ulang Bagian Ini';
      title = 'Bagian ini sudah pernah kamu buka.';
      copy = 'Kalau aksesnya belum tampil, muat ulang halaman ini untuk sinkron ulang isi bagian yang sudah terbuka.';
      note = 'Akses premium tersimpan di akun yang sama, jadi kamu tidak perlu membayar ulang untuk bagian ini.';
    }

    unlockButton.dataset.chapterId = chapter.id;
    unlockButton.dataset.action = action;
    unlockButton.textContent = label;
    if (lockTitle) lockTitle.textContent = title;
    if (lockCopy) lockCopy.textContent = copy;
    if (lockNote) lockNote.textContent = note;
  }

  function handleChapterPrimaryAction(button) {
    const chapterId = Number(button.dataset.chapterId);
    const action = button.dataset.action || 'unlock';
    const chapter = chapterMap[chapterId];

    if (!chapter) return;

    if (action === 'login') {
      DK.redirectToLogin(getChapterReturnUrl(chapterId));
      return;
    }

    if (action === 'topup') {
      DK.redirectToWallet(getChapterReturnUrl(chapterId), {
        source: 'unlock',
        chapter: chapterId,
      });
      return;
    }

    if (action === 'refresh') {
      window.location.href = getChapterReturnUrl(chapterId);
      return;
    }

    unlockSelectedChapter(button);
  }

  function updateReaderMeta(chapter, statusText) {
    const meta = document.querySelector('#chapter-focus-meta');
    const duration = formatDuration(chapter.duration_seconds);
    const metaParts = [`<span>Bagian ${String(chapter.order || '').padStart(2, '0')}</span>`];
    if (duration) {
      metaParts.push(`<span>${duration}</span>`);
    }
    metaParts.push(`<span>${statusText}</span>`);

    if (meta) meta.innerHTML = metaParts.join('');
  }

  function setReaderAccessLoading(isLoading, chapter = null) {
    const loadingState = document.querySelector('#chapter-access-loading');
    const loadingCopy = document.querySelector('#chapter-access-loading-copy');
    const lockState = document.querySelector('#chapter-lock-state');
    const textOutput = document.querySelector('#chapter-text-output');
    const audioShell = document.querySelector('#chapter-audio-shell');
    const videoShell = document.querySelector('#chapter-video-shell');

    if (!loadingState) return;

    if (isLoading) {
      if (chapter) {
        updateReaderMeta(chapter, 'Sedang cek akses');
      }
      loadingState.hidden = false;
      if (loadingCopy) {
        loadingCopy.textContent = chapter?.is_premium
          ? 'Sebentar ya, kami cek dulu apakah bagian premium ini sudah pernah kamu buka.'
          : 'Sebentar ya, kami sedang menyiapkan bagian yang kamu pilih.';
      }
      if (lockState) lockState.hidden = true;
      if (textOutput) textOutput.hidden = true;
      if (audioShell) audioShell.hidden = true;
      if (videoShell) videoShell.hidden = true;
      return;
    }

    loadingState.hidden = true;
  }

  function renderChapter(chapter, {
    unlocked = false,
    feedbackMessage = '',
    scrollIntoView = true,
    syncUrl = true,
  } = {}) {
    if (!chapter) return;

    const reader = document.querySelector('.work-reader');
    const mode = reader?.dataset.mode || 'text';
    const titleNode = document.querySelector('#chapter-focus-title');
    const lockState = document.querySelector('#chapter-lock-state');
    const textOutput = document.querySelector('#chapter-text-output');
    const audioShell = document.querySelector('#chapter-audio-shell');
    const videoShell = document.querySelector('#chapter-video-shell');
    const feedback = document.querySelector('#chapter-focus-feedback');

    if (titleNode) titleNode.textContent = chapter.title || 'Bagian terpilih';
    setReaderAccessLoading(false);

    const chapterIsUnlocked = unlocked || Boolean(chapter.is_unlocked) || !chapter.is_premium;

    if (chapter.is_premium && !chapterIsUnlocked) {
      updateReaderMeta(chapter, 'Perlu dibuka dengan Credit');
      if (lockState) lockState.hidden = false;
      syncLockState(chapter);
      if (feedback) feedback.innerHTML = feedbackMessage ? `<div class="alert alert-success">${feedbackMessage}</div>` : '';
      if (audioShell) {
        audioShell.hidden = true;
        audioShell.innerHTML = '';
      }
      if (videoShell) {
        videoShell.hidden = true;
        videoShell.innerHTML = '';
      }
      if (textOutput) {
        textOutput.hidden = true;
        textOutput.innerHTML = '';
      }
    } else {
      updateReaderMeta(chapter, chapter.is_premium ? 'Sudah dibuka dan siap dinikmati' : 'Siap dinikmati');
      if (lockState) lockState.hidden = true;
      if (feedback) {
        feedback.innerHTML = feedbackMessage ? `<div class="alert alert-success">${feedbackMessage}</div>` : '';
      }

      if (mode === 'audio') {
        if (audioShell) {
          audioShell.hidden = false;
          audioShell.innerHTML = chapter.audio_url
            ? `<audio id="chapter-audio" controls preload="metadata" src="${escapeWorkHtml(chapter.audio_url)}"></audio>`
            : '<div class="work-soft-note">Audio untuk bagian ini belum tersedia.</div>';
        }
        if (videoShell) {
          videoShell.hidden = true;
          videoShell.innerHTML = '';
        }
        if (textOutput) {
          textOutput.hidden = true;
          textOutput.innerHTML = '';
        }
      } else if (mode === 'video') {
        if (videoShell) {
          videoShell.hidden = false;
          videoShell.innerHTML = chapter.video_url
            ? `<video id="chapter-video" controls playsinline preload="metadata" src="${escapeWorkHtml(chapter.video_url)}"></video>`
            : '<div class="work-soft-note">Video untuk episode ini belum tersedia.</div>';
        }
        if (audioShell) {
          audioShell.hidden = true;
          audioShell.innerHTML = '';
        }
        if (textOutput) {
          textOutput.hidden = true;
          textOutput.innerHTML = '';
        }
      } else {
        if (textOutput) {
          textOutput.hidden = false;
          textOutput.innerHTML = nl2Paragraphs(chapter.content || 'Isi bagian ini belum tersedia.');
        }
        if (audioShell) {
          audioShell.hidden = true;
          audioShell.innerHTML = '';
        }
        if (videoShell) {
          videoShell.hidden = true;
          videoShell.innerHTML = '';
        }
      }
    }

    setActiveChapter(chapter.id);
    if (syncUrl && window.history?.replaceState) {
      const url = new URL(window.location.href);
      url.searchParams.set('bagian', chapter.id);
      url.hash = 'fokus-karya';
      window.history.replaceState({}, '', url);
    }

    if (scrollIntoView) {
      document.querySelector('#fokus-karya')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function renderUnlockedChapter(button, payload) {
    const chapterId = Number(button.dataset.chapterId);
    const chapter = {
      ...(chapterMap[chapterId] || {}),
      is_unlocked: true,
      content: payload.content ?? null,
      audio_url: payload.audio_url ?? null,
      video_url: payload.video_url ?? null,
    };
    chapterMap[chapterId] = chapter;
    renderChapter(chapter, {
      unlocked: true,
      feedbackMessage: 'Bagian berhasil dibuka. Selamat menikmati.',
    });
  }

  async function hydrateUnlockedChapters({ selectedChapterId = null, feedbackMessage = '' } = {}) {
    if (!DK.token()) return null;

    const targetChapter = chapterMap[Number(selectedChapterId || getCurrentSelectedChapterId())];
    if (targetChapter?.is_premium) {
      setReaderAccessLoading(true, targetChapter);
    }

    let response = null;
    try {
      response = await DK.get('/works/' + WORK_ID + '/access');
    } catch (_) {
      setReaderAccessLoading(false);
      return null;
    }

    if (!Array.isArray(response?.chapters)) {
      setReaderAccessLoading(false);
      return null;
    }

    response.chapters.forEach((chapter) => {
      const chapterId = Number(chapter.id);
      if (!chapterId || !chapterMap[chapterId]) return;

      chapterMap[chapterId] = {
        ...chapterMap[chapterId],
        ...chapter,
        is_unlocked: Boolean(chapter.is_unlocked),
      };
    });

    const chapterToRender = chapterMap[Number(selectedChapterId || getCurrentSelectedChapterId())];
    if (chapterToRender) {
      renderChapter(chapterToRender, {
        unlocked: Boolean(chapterToRender.is_unlocked),
        feedbackMessage,
        scrollIntoView: false,
        syncUrl: false,
      });
    }

    return response;
  }

  function selectChapterById(chapterId) {
    const chapter = chapterMap[Number(chapterId)];
    if (!chapter) return;
    renderChapter(chapter, { unlocked: false });
  }

  async function unlockSelectedChapter(button) {
    if (!DK.token()) {
      DK.redirectToLogin(getChapterReturnUrl(button.dataset.chapterId));
      return;
    }

    const chapterId = button.dataset.chapterId;
    const chapter = chapterMap[Number(chapterId)];
    const ref = (document.cookie.match(/dk_ref=([^;]+)/) || [])[1];
    const feedback = document.querySelector('#chapter-focus-feedback');
    button.disabled = true;
    if (feedback) feedback.innerHTML = '';

    const { ok, data } = await DK.post('/chapters/' + chapterId + '/unlock', { ref });
    button.disabled = false;

    if (!ok) {
      if (chapter) {
        if (data.reason === 'insufficient_credit') {
          syncLockState(chapter, 'needs_topup');
        } else if (data.reason === 'already_unlocked') {
          await hydrateUnlockedChapters({
            selectedChapterId: Number(chapterId),
            feedbackMessage: data.message || 'Bagian ini sudah pernah kamu buka sebelumnya.',
          });
          return;
        } else {
          syncLockState(chapter);
        }
      }
      if (feedback) {
        feedback.innerHTML = `<div class="alert alert-error">${data.message || 'Bagian belum berhasil dibuka.'}</div>`;
      }
      return;
    }

    DK.refreshCredit();
    renderUnlockedChapter(button, data);
  }

  DK.follow = async function(creatorId) {
    if (!DK.token()) {
      DK.redirectToLogin(DK.currentUrl());
      return;
    }
    alert('Kamu sekarang mengikuti kreator ini.');
  };

  function applyReadingSize(size) {
    const output = document.querySelector('#chapter-text-output');
    if (!output) return;
    output.dataset.readingSize = size;
    localStorage.setItem('dk_reading_size', size);

    document.querySelectorAll('[data-reading-size]').forEach((button) => {
      button.classList.toggle('is-active', button.dataset.readingSize === size);
    });
  }

  document.querySelectorAll('[data-reading-size]').forEach((button) => {
    button.addEventListener('click', () => applyReadingSize(button.dataset.readingSize));
  });

  applyReadingSize(localStorage.getItem('dk_reading_size') || 'normal');

  const initialChapter = chapterMap[Number(@json($selectedChapter?->id))];
  if (initialChapter) {
    renderChapter(initialChapter, {
      unlocked: false,
      scrollIntoView: false,
      syncUrl: false,
    });
  }

  if (DK.token()) {
    hydrateUnlockedChapters();
  }
</script>
@endpush
