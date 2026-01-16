<?php

namespace Tests\Unit\DTOs;

use App\DTOs\UserDTO;
use PHPUnit\Framework\TestCase;

class UserDTOTest extends TestCase
{
    public function test_user_dto_from_array()
    {
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'cpf' => '12345678901',
            'phone' => '11999999999',
            'password' => 'password123',
        ];

        $dto = UserDTO::fromArray($data);

        $this->assertEquals('João Silva', $dto->name);
        $this->assertEquals('joao@example.com', $dto->email);
        $this->assertEquals('12345678901', $dto->cpf);
        $this->assertEquals('11999999999', $dto->phone);
        $this->assertEquals('password123', $dto->password);
    }

    public function test_user_dto_to_array()
    {
        $dto = new UserDTO(
            name: 'João Silva',
            email: 'joao@example.com',
            cpf: '12345678901',
            phone: '11999999999',
            password: 'password123'
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('João Silva', $array['name']);
        $this->assertEquals('joao@example.com', $array['email']);
    }

    public function test_user_dto_with_id()
    {
        $dto = new UserDTO(
            name: 'João Silva',
            email: 'joao@example.com',
            cpf: '12345678901',
            phone: '11999999999',
            password: 'password123',
            id: 1
        );

        $this->assertEquals(1, $dto->id);
    }
}
