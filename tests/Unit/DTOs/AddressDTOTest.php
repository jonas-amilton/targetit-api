<?php

namespace Tests\Unit\DTOs;

use App\DTOs\AddressDTO;
use PHPUnit\Framework\TestCase;

class AddressDTOTest extends TestCase
{
    public function test_address_dto_from_array()
    {
        $data = [
            'user_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'district' => 'Centro',
            'cep' => '12345678',
            'complement' => 'Apt 101',
        ];

        $dto = AddressDTO::fromArray($data);

        $this->assertEquals(1, $dto->user_id);
        $this->assertEquals('Rua das Flores', $dto->street);
        $this->assertEquals('123', $dto->number);
        $this->assertEquals('Centro', $dto->district);
        $this->assertEquals('12345678', $dto->cep);
        $this->assertEquals('Apt 101', $dto->complement);
    }

    public function test_address_dto_to_array()
    {
        $dto = new AddressDTO(
            user_id: 1,
            street: 'Rua das Flores',
            number: '123',
            district: 'Centro',
            cep: '12345678',
            complement: 'Apt 101'
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Rua das Flores', $array['street']);
        $this->assertEquals('Centro', $array['district']);
    }

    public function test_address_dto_without_complement()
    {
        $data = [
            'user_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'district' => 'Centro',
            'cep' => '12345678',
        ];

        $dto = AddressDTO::fromArray($data);

        $this->assertNull($dto->complement);
    }
}
