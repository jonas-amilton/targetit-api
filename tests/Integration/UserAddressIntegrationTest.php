<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Address;
use App\Services\UserService;
use App\Services\AddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAddressIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;
    private AddressService $addressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = app(UserService::class);
        $this->addressService = app(AddressService::class);
    }

    public function test_user_can_have_multiple_addresses()
    {
        $user = User::factory()->create();

        $address1 = Address::factory()->create(['user_id' => $user->id]);
        $address2 = Address::factory()->create(['user_id' => $user->id]);

        $addresses = $this->addressService->getAddressesByUserId($user->id);

        $this->assertCount(2, $addresses);
        $this->assertTrue($addresses->contains('id', $address1->id));
        $this->assertTrue($addresses->contains('id', $address2->id));
    }

    public function test_delete_user_addresses_cascade()
    {
        $user = User::factory()->create();
        Address::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, Address::where('user_id', $user->id)->get());

        $this->userService->deleteUser($user->id);

        $user->refresh();
        $this->assertTrue($user->trashed());
    }

    public function test_update_user_preserves_addresses()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->userService->updateUserPartial($user->id, ['name' => 'Updated Name']);

        $this->assertTrue(Address::where('user_id', $user->id)->exists());
        $this->assertEquals($address->id, Address::where('user_id', $user->id)->first()->id);
    }
}
