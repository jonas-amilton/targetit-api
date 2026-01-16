<?php

namespace Tests\Unit\Services;

use App\DTOs\AddressDTO;
use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Services\AddressService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase;

class AddressServiceTest extends TestCase
{
    private $addressService;
    private $addressRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->addressRepository = \Mockery::mock(AddressRepositoryInterface::class);
        $this->addressService = new AddressService($this->addressRepository);
    }

    public function test_get_all_addresses()
    {
        $addresses = new Collection([
            new Address(['id' => 1, 'user_id' => 1, 'street' => 'Rua A']),
            new Address(['id' => 2, 'user_id' => 1, 'street' => 'Rua B']),
        ]);

        $this->addressRepository->shouldReceive('all')
            ->once()
            ->andReturn($addresses);

        $result = $this->addressService->getAllAddresses();

        $this->assertCount(2, $result);
    }

    public function test_get_addresses_by_user_id()
    {
        $addresses = new Collection([
            new Address(['id' => 1, 'user_id' => 1, 'street' => 'Rua A']),
            new Address(['id' => 2, 'user_id' => 1, 'street' => 'Rua B']),
        ]);

        $this->addressRepository->shouldReceive('findByUserId')
            ->with(1)
            ->once()
            ->andReturn($addresses);

        $result = $this->addressService->getAddressesByUserId(1);

        $this->assertCount(2, $result);
    }

    public function test_create_address()
    {
        $dto = new AddressDTO(
            user_id: 1,
            street: 'Rua Nova',
            number: '123',
            district: 'Centro',
            cep: '12345678'
        );

        $address = new Address([
            'id' => 1,
            'user_id' => 1,
            'street' => 'Rua Nova',
            'number' => '123',
            'district' => 'Centro',
            'cep' => '12345678',
        ]);

        $this->addressRepository->shouldReceive('create')
            ->with($dto)
            ->once()
            ->andReturn($address);

        $result = $this->addressService->createAddress($dto);

        $this->assertEquals($address->street, $result->street);
    }

    public function test_delete_address()
    {
        $this->addressRepository->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(true);

        $result = $this->addressService->deleteAddress(1);

        $this->assertTrue($result);
    }

    public function test_get_address_by_id_not_found()
    {
        $this->addressRepository->shouldReceive('findById')
            ->with(999)
            ->once()
            ->andReturn(null);

        $result = $this->addressService->getAddressById(999);

        $this->assertNull($result);
    }
}
