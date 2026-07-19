<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatorPublishValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_publish_requires_work_synopsis_and_cover(): void
    {
        $creator = $this->createUser([
            'email' => 'publish-cover@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Cerpen Tanpa Cover',
            'synopsis' => '',
            'cover' => null,
            'type' => 'cerpen',
        ]);

        $this->createChapter($work, [
            'content' => 'Isi part yang sebenarnya sudah siap tayang.',
            'status' => 'draft',
        ]);

        Sanctum::actingAs($creator);

        $this->postJson("/api/v1/creator/works/{$work->id}/publish", [])
            ->assertStatus(422)
            ->assertSeeText('Sinopsis karya masih kosong');

        $work->update([
            'synopsis' => 'Sinopsis sudah diisi, tapi cover masih belum ada.',
        ]);

        $this->postJson("/api/v1/creator/works/{$work->id}/publish", [])
            ->assertStatus(422)
            ->assertSeeText('Cover karya belum ada');
    }

    public function test_publish_rejects_premium_part_without_price(): void
    {
        $creator = $this->createUser([
            'email' => 'publish-premium@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Cerpen Premium',
            'synopsis' => 'Sinopsis karya premium yang sudah siap tayang.',
            'cover' => 'work-covers/premium.png',
            'type' => 'cerpen',
        ]);

        $this->createChapter($work, [
            'content' => 'Isi part premium sudah ada dan siap dicek.',
            'is_premium' => true,
            'price_credit' => 0,
            'status' => 'draft',
        ]);

        Sanctum::actingAs($creator);

        $this->postJson("/api/v1/creator/works/{$work->id}/publish", [])
            ->assertStatus(422)
            ->assertSeeText('Harga credit untuk part premium belum diisi');
    }

    public function test_publish_rejects_audio_work_without_duration(): void
    {
        $creator = $this->createUser([
            'email' => 'publish-audio@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Podcast Tanpa Durasi',
            'synopsis' => 'Sinopsis podcast untuk menguji validasi publish audio.',
            'cover' => 'work-covers/podcast.png',
            'type' => 'podcast',
        ]);

        $this->createChapter($work, [
            'audio_url' => 'https://cdn.example.test/audio/podcast.mp3',
            'duration_seconds' => 0,
            'status' => 'draft',
        ]);

        Sanctum::actingAs($creator);

        $this->postJson("/api/v1/creator/works/{$work->id}/publish", [])
            ->assertStatus(422)
            ->assertSeeText('Durasi audio untuk part aktif belum diisi');
    }

    public function test_publish_succeeds_when_required_metadata_is_complete(): void
    {
        $creator = $this->createUser([
            'email' => 'publish-success@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Cerpen Siap Tayang',
            'synopsis' => 'Sinopsis cerpen ini sudah siap tayang dan cukup jelas untuk pembaca.',
            'cover' => 'work-covers/ready.png',
            'type' => 'cerpen',
        ]);

        $chapter = $this->createChapter($work, [
            'title' => 'Part Pembuka',
            'content' => 'Isi part pembuka sudah lengkap dan siap dibaca akun lain.',
            'is_premium' => true,
            'price_credit' => 25,
            'status' => 'draft',
        ]);

        Sanctum::actingAs($creator);

        $this->postJson("/api/v1/creator/works/{$work->id}/publish", [])
            ->assertOk()
            ->assertJsonPath('message', 'Karya berhasil ditayangkan dan sekarang bisa dilihat akun lain.')
            ->assertJsonPath('work.status', 'published')
            ->assertJsonPath('chapter.id', $chapter->id)
            ->assertJsonPath('chapter.status', 'published');

        $work->refresh();
        $chapter->refresh();

        $this->assertSame('published', $work->status);
        $this->assertSame('published', $chapter->status);
        $this->assertNotNull($work->published_at);
        $this->assertNotNull($chapter->published_at);
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Publish ' . $sequence,
            'username' => 'user-publish-' . $sequence,
            'email' => 'user-publish-' . $sequence . '@example.test',
            'password' => 'secret123',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }

    protected function createWork(User $creator, array $overrides = []): Work
    {
        return Work::create(array_merge([
            'creator_id' => $creator->id,
            'title' => 'Draft Publish',
            'type' => 'cerpen',
            'synopsis' => 'Sinopsis default',
            'cover' => 'work-covers/default.png',
            'status' => 'draft',
        ], $overrides));
    }

    protected function createChapter(Work $work, array $overrides = []): Chapter
    {
        return Chapter::create(array_merge([
            'work_id' => $work->id,
            'title' => 'Bagian 1',
            'order' => 1,
            'content' => 'Isi part default',
            'audio_url' => null,
            'video_url' => null,
            'duration_seconds' => null,
            'is_premium' => false,
            'price_credit' => 0,
            'status' => 'draft',
        ], $overrides));
    }
}
