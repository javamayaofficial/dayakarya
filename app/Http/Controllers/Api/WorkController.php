<?php

namespace App\Http\Controllers\Api;

use App\Models\Follow;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
                ->get(['id', 'title', 'order', 'is_premium', 'price_credit', 'duration_seconds']),
        ]);
    }

    public function creatorDashboard(Request $request): JsonResponse
    {
        $this->authorizeCreator($request);

        $user = $request->user();
        $works = $user->works()
            ->with('category:id,name')
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

    public function store(Request $request): JsonResponse
    {
        $this->authorizeCreator($request);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'type'        => ['required', 'in:cerpen,novel,podcast,audio_story,dongeng,motivasi,audiobook'],
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

    protected function authorizeCreator(Request $request): void
    {
        abort_unless($request->user()->hasRole('creator'), 403, 'Khusus creator.');
    }
}
