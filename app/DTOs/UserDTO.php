<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $cpf,
        public string $password,
        public ?int $id = null,
    ) {}

    /**
     * Create a DTO from an array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            cpf: $data['cpf'],
            password: $data['password'],
            id: $data['id'] ?? null,
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'cpf' => $this->cpf,
        ];
    }
}
