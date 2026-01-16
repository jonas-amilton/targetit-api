<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $password = '123456';

        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ], [
            'Accept' => 'application/json',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ])
            ->assertJsonFragment([
                'token_type' => 'Bearer',
            ]);
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('123456'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'senha_errada',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
    }

    public function test_me_requires_token_and_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/auth/me', [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }

    public function test_me_returns_user_when_authenticated(): void
    {
        $password = '123456';

        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ], [
            'Accept' => 'application/json',
        ])->assertOk();

        $token = $login->json('access_token');

        $me = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ]);

        $me->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);
    }

    public function test_logout_invalidates_token(): void
    {
        $password = '123456';

        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ], [
            'Accept' => 'application/json',
        ])->assertOk();

        $token = $login->json('access_token');

        $logout = $this->postJson('/api/auth/logout', [], [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ]);

        $logout->assertNoContent();

        $me = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ]);

        $me->assertStatus(401);
    }
}
