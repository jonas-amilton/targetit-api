<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ControllerRepositoryServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function getToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }

    public function test_full_user_workflow_via_api()
    {
        $response = $this->postJson('/api/auth/users', [
            'name' => 'Integração Test',
            'email' => 'integracao@example.com',
            'cpf' => '99999999999',
            'phone' => '11988888888',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $userId = $response->json('user.id');

        $user = User::find($userId);
        $token = $this->getToken($user);

        $listResponse = $this->getJson('/api/auth/users', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $listResponse->assertStatus(200);

        $updateResponse = $this->putJson(
            "/api/auth/users/$userId",
            ['name' => 'Nome Atualizado'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $updateResponse->assertStatus(200);
        $this->assertEquals('Nome Atualizado', $updateResponse->json('user.name'));

        $deleteResponse = $this->deleteJson(
            "/api/auth/users/$userId",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $deleteResponse->assertStatus(200);
    }

    public function test_full_address_workflow_via_api()
    {
        $user = User::factory()->create();
        $token = $this->getToken($user);

        $createResponse = $this->postJson(
            "/api/auth/users/{$user->id}/addresses",
            [
                'street' => 'Rua de Teste',
                'number' => '999',
                'district' => 'Teste District',
                'cep' => '99999999',
                'complement' => 'Apt 999',
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $createResponse->assertStatus(201);
        $addressId = $createResponse->json('address.id');

        $listResponse = $this->getJson(
            "/api/auth/users/{$user->id}/addresses",
            ['Authorization' => 'Bearer ' . $token]
        );

        $listResponse->assertStatus(200);

        $updateResponse = $this->putJson(
            "/api/auth/users/{$user->id}/addresses/$addressId",
            ['number' => '1000'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $updateResponse->assertStatus(200);

        $deleteResponse = $this->deleteJson(
            "/api/auth/users/{$user->id}/addresses/$addressId",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $deleteResponse->assertStatus(200);
    }

    public function test_full_permission_workflow_via_api()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test_permission']);
        $token = $this->getToken($user);

        $assignResponse = $this->postJson(
            "/api/auth/users/{$user->id}/permissions",
            ['id' => $permission->id],
            ['Authorization' => 'Bearer ' . $token]
        );

        $assignResponse->assertStatus(201);

        $listResponse = $this->getJson(
            "/api/auth/users/{$user->id}/permissions",
            ['Authorization' => 'Bearer ' . $token]
        );

        $listResponse->assertStatus(200);

        $removeResponse = $this->deleteJson(
            "/api/auth/users/{$user->id}/permissions/{$permission->id}",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $removeResponse->assertStatus(200);
    }
}
