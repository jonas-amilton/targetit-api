<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test assigning a permission to a user
     */
    public function test_assign_permission(): void
    {
        $user = User::factory()->create();
        $permission = Permission::factory()->create();
        $token = $this->getAuthToken($user);

        $response = $this->postJson(
            '/api/auth/users/' . $user->id . '/permissions',
            ['id' => $permission->id],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(201);
        $response->assertJsonPath('message', 'Permissão atribuída com sucesso');

        $this->assertTrue($user->permissions()->where('permission_id', $permission->id)->exists());
    }

    /**
     * Test getting user permissions
     */
    public function test_get_user_permissions(): void
    {
        $user = User::factory()->create();
        $permissions = Permission::factory()->count(3)->create();

        foreach ($permissions as $permission) {
            $user->permissions()->attach($permission->id);
        }

        $token = $this->getAuthToken($user);

        $response = $this->getJson(
            '/api/auth/users/' . $user->id . '/permissions',
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(200);
    }

    /**
     * Test removing a permission from a user
     */
    public function test_remove_permission(): void
    {
        $user = User::factory()->create();
        $permission = Permission::factory()->create();

        $user->permissions()->attach($permission->id);

        $token = $this->getAuthToken($user);

        $response = $this->deleteJson(
            '/api/auth/users/' . $user->id . '/permissions/' . $permission->id,
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Permissão removida com sucesso');

        $this->assertFalse($user->permissions()->where('permission_id', $permission->id)->exists());
    }

    /**
     * Helper method to get auth token
     */
    private function getAuthToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
}
