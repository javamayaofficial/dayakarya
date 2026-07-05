<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Follow;
use App\Models\Royalty;
use App\Models\Unlock;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_leaderboard_page(): void
    {
        $this->get('/leaderboard')
            ->assertOk();
    }

    public function test_leaderboard_api_is_public_and_only_returns_published_data(): void
    {
        Cache::flush();

        $creator = $this->createUser([
            'name' => 'Kreator Publik',
            'username' => 'kreator-publik',
            'email' => 'creator@example.test',
        ]);

        $follower = $this->createUser([
            'name' => 'Pengikut',
            'username' => 'pengikut',
            'email' => 'follower@example.test',
        ]);

        Follow::create([
            'follower_id' => $follower->id,
            'creator_id' => $creator->id,
        ]);

        $publishedWork = $this->createWork($creator, [
            'title' => 'Cerita Tayang',
            'status' => 'published',
            'views' => 120,
            'likes_count' => 8,
            'published_at' => now(),
        ]);

        Chapter::create([
            'work_id' => $publishedWork->id,
            'title' => 'Bagian 1',
            'order' => 1,
            'content' => 'Konten publik',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $draftWork = $this->createWork($creator, [
            'title' => 'Cerita Draft',
            'status' => 'draft',
            'views' => 999,
            'likes_count' => 99,
        ]);

        $unlock = Unlock::create([
            'user_id' => $follower->id,
            'chapter_id' => Chapter::create([
                'work_id' => $publishedWork->id,
                'title' => 'Bagian Premium',
                'order' => 2,
                'content' => 'Konten premium',
                'is_premium' => true,
                'price_credit' => 25,
                'status' => 'published',
                'published_at' => now(),
            ])->id,
            'credit_spent' => 25,
        ]);

        Royalty::create([
            'creator_id' => $creator->id,
            'unlock_id' => $unlock->id,
            'amount_rupiah' => 1750,
        ]);

        $response = $this->getJson('/api/v1/leaderboard');

        $response->assertOk()
            ->assertJsonStructure([
                'meta' => ['generated_at', 'works_formula', 'creators_formula'],
                'summary' => ['published_works', 'active_creators', 'total_views', 'total_royalty'],
                'top_works',
                'top_creators',
            ])
            ->assertJsonPath('summary.published_works', 1)
            ->assertJsonPath('summary.active_creators', 1)
            ->assertJsonPath('summary.total_views', 120)
            ->assertJsonPath('summary.total_royalty', 1750)
            ->assertJsonPath('top_works.0.id', $publishedWork->id)
            ->assertJsonPath('top_creators.0.id', $creator->id);

        $topWorkIds = collect($response->json('top_works'))->pluck('id');

        $this->assertTrue($topWorkIds->contains($publishedWork->id));
        $this->assertFalse($topWorkIds->contains($draftWork->id));
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User ' . $sequence,
            'username' => 'user-' . $sequence,
            'email' => 'user-' . $sequence . '@example.test',
            'password' => 'password',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }

    protected function createWork(User $creator, array $overrides = []): Work
    {
        return Work::create(array_merge([
            'creator_id' => $creator->id,
            'title' => 'Work Default',
            'type' => 'cerpen',
            'synopsis' => 'Sinopsis',
            'status' => 'draft',
            'views' => 0,
            'likes_count' => 0,
        ], $overrides));
    }
}
