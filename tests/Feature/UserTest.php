<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a user via API
     */
    public function test_create_user(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '11999999999',
            'cpf' => '12345678901',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/users', $userData);
        $response->assertStatus(201);
        $response->assertJsonPath('message', 'Usuário criado com sucesso');
    }

    /**
     * Test listing users (requires authentication)
     */
    public function test_list_users(): void
    {
        $user = User::factory()->create();
        User::factory()->count(5)->create();

        $token = $this->getAuthToken($user);

        $response = $this->getJson('/api/auth/users', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test getting a specific user
     */
    public function test_get_user(): void
    {
        $user = User::factory()->create();
        $token = $this->getAuthToken($user);

        $response = $this->getJson('/api/auth/users/' . $user->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('name', $user->name);
    }

    /**
     * Test updating a user
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create();
        $token = $this->getAuthToken($user);

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '11888888888'
        ];

        $response = $this->putJson('/api/auth/users/' . $user->id, $updateData, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Usuário atualizado com sucesso');
    }

    /**
     * Test deleting a user (soft delete)
     */
    public function test_delete_user(): void
    {
        $user = User::factory()->create();
        $token = $this->getAuthToken($user);

        $response = $this->deleteJson('/api/auth/users/' . $user->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Usuário deletado com sucesso');
    }

    /**
     * Test user validation
     */
    public function test_create_user_validation(): void
    {
        $response = $this->postJson('/api/auth/users', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'cpf' => '123',
            'password' => 'short'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Helper method to get auth token
     */
    private function getAuthToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
}
