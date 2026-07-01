<?php

namespace App\Http\Controllers\Api;

use App\Models\Follow;
use App\Models\Royalty;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends \App\Http\Controllers\Controller
{
    public function index(): JsonResponse
    {
        $payload = Cache::remember('dayakarya.leaderboard.public.v1', now()->addMinutes(10), function (): array {
            $topWorks = Work::query()
                ->published()
                ->with('creator:id,name', 'category:id,name')
                ->select([
                    'id',
                    'creator_id',
                    'category_id',
                    'title',
                    'slug',
                    'type',
                    'cover',
                    'views',
                    'likes_count',
                    'published_at',
                ])
                ->selectRaw('(views + (likes_count * 20)) as leaderboard_score')
                ->orderByDesc('leaderboard_score')
                ->orderByDesc('views')
                ->limit(12)
                ->get();

            $workAgg = Work::query()
                ->published()
                ->selectRaw('creator_id, COUNT(*) as works_count, COALESCE(SUM(views), 0) as total_views, COALESCE(SUM(likes_count), 0) as total_likes')
                ->groupBy('creator_id');

            $royaltyAgg = Royalty::query()
                ->selectRaw('creator_id, COALESCE(SUM(amount_rupiah), 0) as total_royalty')
                ->groupBy('creator_id');

            $followAgg = Follow::query()
                ->selectRaw('creator_id, COUNT(*) as total_followers')
                ->groupBy('creator_id');

            $topCreators = User::query()
                ->joinSub($workAgg, 'work_agg', fn ($join) => $join->on('users.id', '=', 'work_agg.creator_id'))
                ->leftJoinSub($royaltyAgg, 'royalty_agg', fn ($join) => $join->on('users.id', '=', 'royalty_agg.creator_id'))
                ->leftJoinSub($followAgg, 'follow_agg', fn ($join) => $join->on('users.id', '=', 'follow_agg.creator_id'))
                ->select([
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.avatar',
                    'users.bio',
                ])
                ->selectRaw('work_agg.works_count')
                ->selectRaw('work_agg.total_views')
                ->selectRaw('work_agg.total_likes')
                ->selectRaw('COALESCE(royalty_agg.total_royalty, 0) as total_royalty')
                ->selectRaw('COALESCE(follow_agg.total_followers, 0) as total_followers')
                ->selectRaw('(work_agg.total_views + (work_agg.total_likes * 20) + (COALESCE(follow_agg.total_followers, 0) * 40) + FLOOR(COALESCE(royalty_agg.total_royalty, 0) / 1000) + (work_agg.works_count * 80)) as leaderboard_score')
                ->orderByDesc('leaderboard_score')
                ->orderByDesc('work_agg.total_views')
                ->limit(12)
                ->get();

            return [
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                    'works_formula' => 'views + likes x 20',
                    'creators_formula' => 'views + likes x 20 + followers x 40 + royalty/1000 + works x 80',
                ],
                'summary' => [
                    'published_works' => (int) Work::query()->published()->count(),
                    'active_creators' => (int) Work::query()->published()->distinct('creator_id')->count('creator_id'),
                    'total_views' => (int) Work::query()->published()->sum('views'),
                    'total_royalty' => (int) Royalty::query()->sum('amount_rupiah'),
                ],
                'top_works' => $topWorks,
                'top_creators' => $topCreators,
            ];
        });

        return response()->json($payload);
    }
}
