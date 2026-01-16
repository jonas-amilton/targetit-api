<?php

namespace App\DTOs;

class AddressDTO
{
    public function __construct(
        public int $user_id,
        public string $street,
        public string $number,
        public string $district,
        public string $cep,
        public ?string $complement = null,
        public ?int $id = null,
    ) {}

    /**
     * Create a DTO from an array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            street: $data['street'],
            number: $data['number'],
            district: $data['district'],
            cep: $data['cep'],
            complement: $data['complement'] ?? null,
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
            'user_id' => $this->user_id,
            'street' => $this->street,
            'number' => $this->number,
            'district' => $this->district,
            'cep' => $this->cep,
            'complement' => $this->complement,
        ];
    }
}
