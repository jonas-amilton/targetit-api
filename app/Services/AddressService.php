<?php

namespace App\Services;

use App\DTOs\AddressDTO;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function __construct(private AddressRepositoryInterface $addressRepository) {}

    /**
     * Get all addresses
     */
    public function getAllAddresses(): Collection
    {
        return $this->addressRepository->all();
    }

    /**
     * Get addresses by user ID
     */
    public function getAddressesByUserId(int $userId): Collection
    {
        return $this->addressRepository->findByUserId($userId);
    }

    /**
     * Get address by ID
     */
    public function getAddressById(int $id): ?Address
    {
        return $this->addressRepository->findById($id);
    }

    /**
     * Create a new address
     */
    public function createAddress(AddressDTO $dto): Address
    {
        return $this->addressRepository->create($dto);
    }

    /**
     * Update an address
     */
    public function updateAddress(int $id, AddressDTO $dto): bool
    {
        return $this->addressRepository->update($id, $dto);
    }

    /**
     * Delete an address
     */
    public function deleteAddress(int $id): bool
    {
        return $this->addressRepository->delete($id);
    }
}
