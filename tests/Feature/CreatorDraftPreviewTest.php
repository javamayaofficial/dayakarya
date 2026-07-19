<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatorDraftPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_work_is_not_available_from_public_show_endpoint(): void
    {
        $creator = $this->createUser([
            'email' => 'draft-public@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Draft Rahasia',
            'status' => 'draft',
        ]);

        $this->getJson("/api/v1/works/{$work->id}")
            ->assertNotFound();
    }

    public function test_owner_can_open_draft_in_creator_preview_endpoint(): void
    {
        $creator = $this->createUser([
            'email' => 'draft-owner@example.test',
        ]);

        $work = $this->createWork($creator, [
            'title' => 'Draft Internal',
            'type' => 'cerpen',
            'synopsis' => 'Sinopsis untuk preview internal',
            'status' => 'draft',
        ]);

        $chapter = $this->createChapter($work, [
            'title' => 'Bagian 1',
            'order' => 1,
            'content' => 'Konten preview untuk kreator sendiri.',
            'status' => 'draft',
            'is_premium' => false,
            'price_credit' => 0,
        ]);

        Sanctum::actingAs($creator);

        $this->getJson("/api/v1/creator/works/{$work->id}")
            ->assertOk()
            ->assertJsonPath('work.id', $work->id)
            ->assertJsonPath('work.title', 'Draft Internal')
            ->assertJsonPath('work.status', 'draft')
            ->assertJsonPath('chapters.0.id', $chapter->id)
            ->assertJsonPath('chapters.0.status', 'draft')
            ->assertJsonPath('editor.chapter_id', $chapter->id)
            ->assertJsonPath('editor.chapter_title', 'Bagian 1')
            ->assertJsonPath('editor.content', 'Konten preview untuk kreator sendiri.');
    }

    public function test_other_member_cannot_open_someone_else_draft_preview(): void
    {
        $creator = $this->createUser([
            'email' => 'draft-owner-2@example.test',
        ]);

        $otherMember = $this->createUser([
            'email' => 'draft-other@example.test',
        ]);

        $work = $this->createWork($creator, [
            'status' => 'draft',
        ]);

        $this->createChapter($work, [
            'title' => 'Bagian Terlarang',
            'content' => 'Konten ini tidak boleh dibuka akun lain.',
            'status' => 'draft',
        ]);

        Sanctum::actingAs($otherMember);

        $this->getJson("/api/v1/creator/works/{$work->id}")
            ->assertForbidden();
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Draft ' . $sequence,
            'username' => 'user-draft-' . $sequence,
            'email' => 'user-draft-' . $sequence . '@example.test',
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
            'title' => 'Draft Preview',
            'type' => 'cerpen',
            'synopsis' => 'Sinopsis draft preview',
            'status' => 'draft',
        ], $overrides));
    }

    protected function createChapter(Work $work, array $overrides = []): Chapter
    {
        return Chapter::create(array_merge([
            'work_id' => $work->id,
            'title' => 'Bagian 1',
            'order' => 1,
            'content' => 'Isi draft',
            'is_premium' => false,
            'price_credit' => 0,
            'status' => 'draft',
        ], $overrides));
    }
}
