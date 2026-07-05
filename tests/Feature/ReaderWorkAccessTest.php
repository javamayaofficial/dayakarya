<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Unlock;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReaderWorkAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_premium_access_endpoint_requires_login(): void
    {
        $creator = $this->createUser([
            'name' => 'Kreator',
            'username' => 'kreator',
            'email' => 'creator-access@example.test',
        ]);

        $work = $this->createPublishedWork($creator);

        $this->getJson("/api/v1/works/{$work->id}/access")
            ->assertUnauthorized();
    }

    public function test_creator_can_access_own_premium_chapters_without_unlock_record(): void
    {
        $creator = $this->createUser([
            'name' => 'Kreator Pemilik',
            'username' => 'kreator-pemilik',
            'email' => 'creator-owner@example.test',
        ]);

        $work = $this->createPublishedWork($creator);
        $premiumChapter = $this->createChapter($work, [
            'title' => 'Bagian Premium',
            'order' => 1,
            'content' => 'Konten premium kreator',
            'is_premium' => true,
            'price_credit' => 30,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Sanctum::actingAs($creator);

        $response = $this->getJson("/api/v1/works/{$work->id}/access");

        $response->assertOk()
            ->assertJsonPath('work_id', $work->id)
            ->assertJsonPath('unlocked_chapter_ids.0', $premiumChapter->id)
            ->assertJsonPath('chapters.0.id', $premiumChapter->id)
            ->assertJsonPath('chapters.0.is_unlocked', true)
            ->assertJsonPath('chapters.0.content', 'Konten premium kreator');
    }

    public function test_member_only_receives_content_for_unlocked_premium_chapters(): void
    {
        $creator = $this->createUser([
            'name' => 'Kreator Biasa',
            'username' => 'kreator-biasa',
            'email' => 'creator-buyer@example.test',
        ]);

        $buyer = $this->createUser([
            'name' => 'Pembaca',
            'username' => 'pembaca',
            'email' => 'buyer@example.test',
        ]);

        $work = $this->createPublishedWork($creator);

        $freeChapter = $this->createChapter($work, [
            'title' => 'Bagian Gratis',
            'order' => 1,
            'content' => 'Konten gratis',
            'is_premium' => false,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $lockedPremiumChapter = $this->createChapter($work, [
            'title' => 'Bagian Premium Terkunci',
            'order' => 2,
            'content' => 'Konten premium terkunci',
            'is_premium' => true,
            'price_credit' => 20,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $unlockedPremiumChapter = $this->createChapter($work, [
            'title' => 'Bagian Premium Terbuka',
            'order' => 3,
            'content' => 'Konten premium terbuka',
            'is_premium' => true,
            'price_credit' => 25,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Unlock::create([
            'user_id' => $buyer->id,
            'chapter_id' => $unlockedPremiumChapter->id,
            'credit_spent' => 25,
        ]);

        Sanctum::actingAs($buyer);

        $response = $this->getJson("/api/v1/works/{$work->id}/access");

        $response->assertOk()
            ->assertJsonPath('work_id', $work->id)
            ->assertJsonPath('unlocked_chapter_ids.0', $unlockedPremiumChapter->id)
            ->assertJsonPath('chapters.0.id', $freeChapter->id)
            ->assertJsonPath('chapters.0.is_unlocked', true)
            ->assertJsonPath('chapters.0.content', 'Konten gratis')
            ->assertJsonPath('chapters.1.id', $lockedPremiumChapter->id)
            ->assertJsonPath('chapters.1.is_unlocked', false)
            ->assertJsonPath('chapters.1.content', null)
            ->assertJsonPath('chapters.2.id', $unlockedPremiumChapter->id)
            ->assertJsonPath('chapters.2.is_unlocked', true)
            ->assertJsonPath('chapters.2.content', 'Konten premium terbuka');
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 100;

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

    protected function createPublishedWork(User $creator, array $overrides = []): Work
    {
        return Work::create(array_merge([
            'creator_id' => $creator->id,
            'title' => 'Karya Premium',
            'type' => 'cerpen',
            'synopsis' => 'Sinopsis karya premium',
            'status' => 'published',
            'published_at' => now(),
        ], $overrides));
    }

    protected function createChapter(Work $work, array $overrides = []): Chapter
    {
        return Chapter::create(array_merge([
            'work_id' => $work->id,
            'title' => 'Bagian',
            'order' => 1,
            'content' => 'Isi bagian',
            'is_premium' => false,
            'price_credit' => 0,
            'status' => 'draft',
        ], $overrides));
    }
}
