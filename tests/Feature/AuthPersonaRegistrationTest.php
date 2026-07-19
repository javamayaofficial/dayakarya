<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AuthPersonaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'operator', 'creator', 'reader', 'listener', 'affiliate', 'sponsor', 'csr'] as $role) {
            Role::findOrCreate($role);
        }

        Http::fake();
    }

    public function test_reader_registration_assigns_reader_role_and_redirects_to_explore(): void
    {
        $response = $this->postJson('/api/v1/auth/register', $this->registrationPayload([
            'email' => 'reader@example.test',
            'phone' => '081111111111',
            'persona' => 'reader',
        ]));

        $response->assertCreated()
            ->assertJsonPath('message', 'Registrasi berhasil.')
            ->assertJsonPath('roles.0', 'reader')
            ->assertJsonPath('redirect_to', '/explore');

        $user = User::where('email', 'reader@example.test')->firstOrFail();

        $this->assertTrue($user->hasRole('reader'));
        $this->assertFalse($user->hasCreatorAccess());
        $this->assertNotNull($user->wallet);
    }

    public function test_writer_registration_assigns_creator_role_and_redirects_to_creator(): void
    {
        $response = $this->postJson('/api/v1/auth/register', $this->registrationPayload([
            'email' => 'writer@example.test',
            'phone' => '082222222222',
            'persona' => 'writer',
        ]));

        $response->assertCreated()
            ->assertJsonPath('roles.0', 'creator')
            ->assertJsonPath('redirect_to', '/creator');

        $user = User::where('email', 'writer@example.test')->firstOrFail();

        $this->assertTrue($user->hasRole('creator'));
        $this->assertTrue($user->hasCreatorAccess());
    }

    public function test_listener_creator_registration_assigns_listener_role_and_redirects_to_creator(): void
    {
        $response = $this->postJson('/api/v1/auth/register', $this->registrationPayload([
            'email' => 'listener@example.test',
            'phone' => '083333333333',
            'persona' => 'listener_creator',
        ]));

        $response->assertCreated()
            ->assertJsonPath('roles.0', 'listener')
            ->assertJsonPath('redirect_to', '/creator');

        $user = User::where('email', 'listener@example.test')->firstOrFail();

        $this->assertTrue($user->hasRole('listener'));
        $this->assertTrue($user->hasCreatorAccess());
    }

    public function test_reader_login_redirects_to_explore(): void
    {
        $user = $this->createUser([
            'email' => 'login-reader@example.test',
            'phone' => '084444444444',
            'password' => 'secret123',
        ]);
        $user->assignRole('reader');

        $this->postJson('/api/v1/auth/login', [
            'email' => 'login-reader@example.test',
            'password' => 'secret123',
        ])->assertOk()
            ->assertJsonPath('roles.0', 'reader')
            ->assertJsonPath('redirect_to', '/explore');
    }

    public function test_creator_login_redirects_to_creator_area(): void
    {
        $user = $this->createUser([
            'email' => 'login-creator@example.test',
            'phone' => '085555555555',
            'password' => 'secret123',
        ]);
        $user->assignRole('creator');

        $this->postJson('/api/v1/auth/login', [
            'email' => 'login-creator@example.test',
            'password' => 'secret123',
        ])->assertOk()
            ->assertJsonPath('roles.0', 'creator')
            ->assertJsonPath('redirect_to', '/creator');
    }

    public function test_registration_requires_persona_selection(): void
    {
        $payload = $this->registrationPayload([
            'email' => 'missing-persona@example.test',
            'phone' => '086666666666',
        ]);
        unset($payload['persona']);

        $this->postJson('/api/v1/auth/register', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['persona']);
    }

    protected function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'User Persona',
            'email' => 'persona@example.test',
            'phone' => '080000000000',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'persona' => 'reader',
        ], $overrides);
    }

    protected function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $user = User::create(array_merge([
            'name' => 'User Login ' . $sequence,
            'username' => 'user-login-' . $sequence,
            'email' => 'user-login-' . $sequence . '@example.test',
            'phone' => '0899900000' . $sequence,
            'password' => 'secret123',
            'status' => 'active',
        ], $overrides));

        $sequence++;

        return $user;
    }
}
