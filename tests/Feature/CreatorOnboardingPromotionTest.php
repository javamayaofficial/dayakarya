<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CreatorOnboardingPromotionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'operator', 'creator', 'reader', 'listener', 'affiliate', 'sponsor', 'csr'] as $role) {
            Role::findOrCreate($role);
        }
    }

    public function test_reader_is_promoted_to_creator_when_starting_text_work(): void
    {
        $reader = $this->createUser([
            'email' => 'reader-promote-text@example.test',
        ]);
        $reader->assignRole('reader');

        Sanctum::actingAs($reader);

        $this->postJson('/api/v1/works', [
            'title' => 'Cerpen Perdana',
            'type' => 'cerpen',
            'synopsis' => 'Draft pertama untuk jalur penulis.',
        ])->assertCreated()
            ->assertJsonPath('message', 'Karya tersimpan sebagai draft.')
            ->assertJsonPath('work.title', 'Cerpen Perdana')
            ->assertJsonPath('work.type', 'cerpen')
            ->assertJsonPath('work.status', 'draft');

        $reader->refresh();

        $this->assertTrue($reader->hasRole('creator'));
        $this->assertTrue($reader->hasRole('reader'));
        $this->assertTrue($reader->hasCreatorAccess());
        $this->assertSame('/creator', $reader->defaultInternalPath());
    }

    public function test_reader_is_promoted_to_listener_when_starting_audio_work(): void
    {
        $reader = $this->createUser([
            'email' => 'reader-promote-audio@example.test',
        ]);
        $reader->assignRole('reader');

        Sanctum::actingAs($reader);

        $this->postJson('/api/v1/works', [
            'title' => 'Podcast Perdana',
            'type' => 'podcast',
            'synopsis' => 'Draft pertama untuk jalur audio.',
        ])->assertCreated()
            ->assertJsonPath('message', 'Karya tersimpan sebagai draft.')
            ->assertJsonPath('work.title', 'Podcast Perdana')
            ->assertJsonPath('work.type', 'podcast')
            ->assertJsonPath('work.status', 'draft');

        $reader->refresh();

        $this->assertTrue($reader->hasRole('listener'));
        $this->assertTrue($reader->hasRole('reader'));
        $this->assertTrue($reader->hasCreatorAccess());
        $this->assertSame('/creator', $reader->defaultInternalPath());
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Promotion ' . $sequence,
            'username' => 'user-promotion-' . $sequence,
            'email' => 'user-promotion-' . $sequence . '@example.test',
            'phone' => '0877700000' . $sequence,
            'password' => 'secret123',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }
}
