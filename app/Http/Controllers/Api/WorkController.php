<?php

namespace App\Http\Controllers\Api;

use App\Models\Chapter;
use App\Models\Follow;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * Katalog karya: explore, trending, detail, pencarian, dan CRUD oleh creator.
 */
class WorkController extends \App\Http\Controllers\Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Work::published()->with('creator:id,name', 'category:id,name');

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $q->whereHas('category', fn ($c) => $c->where('slug', $request->category));
        }
        if ($request->filled('search')) {
            $q->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->boolean('trending')) {
            $q->trending();
        } else {
            $q->latest('published_at');
        }

        return response()->json($q->paginate(15));
    }

    public function show(Work $work): JsonResponse
    {
        abort_unless($work->status === 'published', 404);
        $work->increment('views');

        return response()->json([
            'work' => $work->load('creator:id,name,avatar', 'category:id,name', 'tags:id,name'),
            'chapters' => $work->chapters()
                ->where('status', 'published')
                ->get(['id', 'title', 'order', 'is_premium', 'price_credit', 'duration_seconds', 'audio_url', 'video_url']),
        ]);
    }

    public function creatorDashboard(Request $request): JsonResponse
    {
        $this->authorizeMember($request);

        $user = $request->user();
        $works = $user->works()
            ->with('category:id,name')
            ->withCount('chapters')
            ->withCount([
                'chapters as published_chapters_count' => fn ($query) => $query->where('status', 'published'),
                'chapters as ready_chapters_count' => fn ($query) => $query->where(function ($chapterQuery) {
                    $chapterQuery
                        ->where(function ($textQuery) {
                            $textQuery->whereNotNull('content')->where('content', '!=', '');
                        })
                        ->orWhere(function ($audioQuery) {
                            $audioQuery->whereNotNull('audio_url')->where('audio_url', '!=', '');
                        })
                        ->orWhere(function ($videoQuery) {
                            $videoQuery->whereNotNull('video_url')->where('video_url', '!=', '');
                        });
                }),
            ])
            ->latest()
            ->get([
                'id',
                'title',
                'slug',
                'type',
                'status',
                'views',
                'likes_count',
                'published_at',
                'cover',
                'category_id',
            ]);

        return response()->json([
            'stats' => [
                'works' => $works->count(),
                'views' => (int) $works->sum('views'),
                'royalty_rupiah' => (int) $user->royalties()->sum('amount_rupiah'),
                'followers' => Follow::query()->where('creator_id', $user->id)->count(),
            ],
            'works' => $works,
        ]);
    }

    public function creatorShow(Request $request, Work $work): JsonResponse
    {
        $this->authorizeOwnership($request, $work);

        $chapters = $work->chapters()
            ->orderBy('order')
            ->get([
                'id',
                'title',
                'order',
                'status',
                'is_premium',
                'price_credit',
            ]);

        $requestedChapterId = $request->integer('chapter_id');
        $activeChapter = $requestedChapterId
            ? $work->chapters()->find($requestedChapterId)
            : $work->chapters()->orderBy('order')->first();

        if ($activeChapter === null && $chapters->isNotEmpty()) {
            $activeChapter = $work->chapters()->find($chapters->first()->id);
        }

        return response()->json([
            'work' => $work->load('category:id,name'),
            'chapters' => $chapters->map(fn (Chapter $chapter) => $this->serializeCreatorChapter($chapter))->values(),
            'editor' => $this->serializeEditorChapter($activeChapter),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeMember($request);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'type'        => ['required', 'in:cerpen,novel,podcast,audio_story,video_series,dongeng,motivasi,audiobook'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'synopsis'    => ['nullable', 'string'],
        ]);

        $work = $request->user()->works()->create([
            ...$data,
            'status' => 'draft',
        ]);

        return response()->json([
            'message' => 'Karya tersimpan sebagai draft.',
            'work'    => $work,
        ], 201);
    }

    public function creatorUpdate(Request $request, Work $work): JsonResponse
    {
        $this->authorizeOwnership($request, $work);

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:150'],
            'type'             => ['required', 'in:cerpen,novel,podcast,audio_story,video_series,dongeng,motivasi,audiobook'],
            'category_id'      => ['nullable', 'exists:categories,id'],
            'synopsis'         => ['nullable', 'string'],
            'chapter_id'       => ['nullable', 'integer'],
            'chapter_title'    => ['nullable', 'string', 'max:150'],
            'content'          => ['nullable', 'string'],
            'audio_url'        => ['nullable', 'url'],
            'video_url'        => ['nullable', 'url'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'is_premium'       => ['nullable', 'boolean'],
            'price_credit'     => ['nullable', 'integer', 'min:0'],
        ]);

        $work->update([
            'title' => $data['title'],
            'type' => $data['type'],
            'category_id' => $data['category_id'] ?? null,
            'synopsis' => $data['synopsis'] ?? null,
        ]);

        $chapter = null;

        if (!empty($data['chapter_id'])) {
            $chapter = $work->chapters()->find($data['chapter_id']);
            abort_unless($chapter !== null, 404, 'Bagian yang mau kamu edit tidak ditemukan.');
        }

        $chapter = $chapter ?: $work->chapters()->orderBy('order')->first() ?: new Chapter([
            'order' => 1,
            'status' => 'draft',
        ]);
        $defaultChapterTitle = 'Bagian ' . ((int) ($chapter->order ?: 1));

        $chapter->fill([
            'title' => $data['chapter_title'] ?: $defaultChapterTitle,
            'content' => $data['content'] ?? null,
            'audio_url' => $data['audio_url'] ?? null,
            'video_url' => $data['video_url'] ?? null,
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'is_premium' => (bool) ($data['is_premium'] ?? false),
            'price_credit' => (int) ($data['price_credit'] ?? 0),
        ]);

        $work->chapters()->save($chapter);

        return response()->json([
            'message' => 'Draft berhasil diperbarui.',
            'work' => $work->fresh(),
            'chapter' => $this->serializeCreatorChapter($chapter->fresh()),
            'chapters' => $work->chapters()
                ->orderBy('order')
                ->get()
                ->map(fn (Chapter $item) => $this->serializeCreatorChapter($item))
                ->values(),
            'editor' => $this->serializeEditorChapter($chapter->fresh()),
        ]);
    }

    public function creatorStoreChapter(Request $request, Work $work): JsonResponse
    {
        $this->authorizeOwnership($request, $work);

        $nextOrder = (int) $work->chapters()->max('order') + 1;
        $chapter = $work->chapters()->create([
            'title' => 'Bagian ' . $nextOrder,
            'order' => $nextOrder,
            'status' => 'draft',
            'is_premium' => false,
            'price_credit' => 0,
        ]);

        return response()->json([
            'message' => 'Bagian baru siap dilanjutkan.',
            'chapter' => $this->serializeCreatorChapter($chapter),
            'chapters' => $work->chapters()
                ->orderBy('order')
                ->get()
                ->map(fn (Chapter $item) => $this->serializeCreatorChapter($item))
                ->values(),
            'editor' => $this->serializeEditorChapter($chapter),
        ], 201);
    }

    public function creatorUploadCover(Request $request, Work $work): JsonResponse
    {
        $this->authorizeOwnership($request, $work);

        $data = $request->validate([
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $previousCover = $work->getRawOriginal('cover');
        if ($previousCover && ! Str::startsWith($previousCover, ['http://', 'https://', '/storage/'])) {
            Storage::disk('public')->delete($previousCover);
        }

        $extension = $data['cover']->getClientOriginalExtension() ?: 'jpg';
        $filename = 'work-' . $work->id . '-cover-' . time() . '.' . strtolower($extension);
        $path = $data['cover']->storeAs('work-covers', $filename, 'public');

        $work->update([
            'cover' => $path,
        ]);

        return response()->json([
            'message' => 'Cover karya berhasil diunggah.',
            'cover' => $work->fresh()->cover,
            'work' => $work->fresh(),
        ]);
    }

    public function creatorPublish(Request $request, Work $work): JsonResponse
    {
        $this->authorizeOwnership($request, $work);

        $data = $request->validate([
            'chapter_id' => ['nullable', 'integer'],
        ]);

        $chapter = null;

        if (!empty($data['chapter_id'])) {
            $chapter = $work->chapters()->find($data['chapter_id']);
            abort_unless($chapter !== null, 404, 'Bagian yang mau ditayangkan tidak ditemukan.');
        }

        $chapter = $chapter ?: $work->chapters()->orderBy('order')->first();
        abort_unless($chapter !== null, 422, 'Buat dulu minimal satu part sebelum ditayangkan.');

        $hasContent = filled($chapter->content)
            || filled($chapter->audio_url)
            || filled($chapter->video_url);

        abort_unless($hasContent, 422, 'Isi part aktif masih kosong. Lengkapi dulu sebelum ditayangkan.');

        $publishTime = Carbon::now();

        $work->forceFill([
            'status' => 'published',
            'published_at' => $work->published_at ?: $publishTime,
        ])->save();

        $chapter->forceFill([
            'status' => 'published',
            'published_at' => $chapter->published_at ?: $publishTime,
        ])->save();

        return response()->json([
            'message' => 'Karya berhasil ditayangkan dan sekarang bisa dilihat akun lain.',
            'work' => $work->fresh(['category:id,name']),
            'chapter' => $this->serializeCreatorChapter($chapter->fresh()),
            'chapters' => $work->chapters()
                ->orderBy('order')
                ->get()
                ->map(fn (Chapter $item) => $this->serializeCreatorChapter($item))
                ->values(),
            'editor' => $this->serializeEditorChapter($chapter->fresh()),
        ]);
    }

    protected function authorizeMember(Request $request): void
    {
        abort_unless($request->user() !== null, 401, 'Silakan login dulu.');
    }

    protected function authorizeOwnership(Request $request, Work $work): void
    {
        $this->authorizeMember($request);
        abort_unless($work->creator_id === $request->user()->id, 403, 'Karya ini bukan milik akun kamu.');
    }

    protected function serializeCreatorChapter(Chapter $chapter): array
    {
        return [
            'id' => $chapter->id,
            'title' => $chapter->title ?: ('Bagian ' . ((int) ($chapter->order ?: 1))),
            'order' => (int) ($chapter->order ?: 1),
            'status' => $chapter->status ?: 'draft',
            'is_premium' => (bool) $chapter->is_premium,
            'price_credit' => (int) ($chapter->price_credit ?? 0),
        ];
    }

    protected function serializeEditorChapter(?Chapter $chapter): array
    {
        $chapterOrder = (int) ($chapter?->order ?: 1);

        return [
            'chapter_id' => $chapter?->id,
            'chapter_title' => $chapter?->title ?: ('Bagian ' . $chapterOrder),
            'content' => $chapter?->content,
            'audio_url' => $chapter?->audio_url,
            'video_url' => $chapter?->video_url,
            'duration_seconds' => $chapter?->duration_seconds,
            'is_premium' => (bool) ($chapter?->is_premium ?? false),
            'price_credit' => (int) ($chapter?->price_credit ?? 0),
        ];
    }
}
