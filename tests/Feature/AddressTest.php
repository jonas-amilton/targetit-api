<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an address
     */
    public function test_create_address(): void
    {
        $user = User::factory()->create();
        $token = $this->getAuthToken($user);

        $addressData = [
            'user_id' => $user->id,
            'street' => 'Rua das Flores',
            'number' => '123',
            'district' => 'Centro',
            'cep' => '12345678',
            'complement' => 'Apt 101'
        ];

        $response = $this->postJson('/api/auth/users/' . $user->id . '/addresses', $addressData, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('message', 'Endereço criado com sucesso');
    }

    /**
     * Test listing user addresses
     */
    public function test_list_user_addresses(): void
    {
        $user = User::factory()->create();
        Address::factory()->count(3)->create(['user_id' => $user->id]);
        $token = $this->getAuthToken($user);

        $response = $this->getJson('/api/auth/users/' . $user->id . '/addresses', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test getting a specific address
     */
    public function test_get_address(): void
    {
        $address = Address::factory()->create();
        $token = $this->getAuthToken($address->user);

        $response = $this->getJson('/api/auth/users/' . $address->user_id . '/addresses/' . $address->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('street', $address->street);
    }

    /**
     * Test updating an address
     */
    public function test_update_address(): void
    {
        $address = Address::factory()->create();
        $token = $this->getAuthToken($address->user);

        $updateData = [
            'street' => 'Rua Atualizada',
            'number' => '456'
        ];

        $response = $this->putJson(
            '/api/auth/users/' . $address->user_id . '/addresses/' . $address->id,
            $updateData,
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Endereço atualizado com sucesso');
    }

    /**
     * Test deleting an address
     */
    public function test_delete_address(): void
    {
        $address = Address::factory()->create();
        $token = $this->getAuthToken($address->user);

        $response = $this->deleteJson(
            '/api/auth/users/' . $address->user_id . '/addresses/' . $address->id,
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Endereço deletado com sucesso');

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    /**
     * Helper method to get auth token
     */
    private function getAuthToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
}
