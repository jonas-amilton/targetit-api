<?php

namespace App\Http\Controllers\Api\Address;

use App\DTOs\AddressDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    /**
     * Update the specified address
     */
    public function __invoke(UpdateAddressRequest $request, int $user, int $address): JsonResponse
    {
        $addressModel = $this->addressService->getAddressById($address);

        if (!$addressModel || $addressModel->user_id !== $user) {
            return response()->json([
                'message' => 'Endereço não encontrado'
            ], 404);
        }

        $data = $request->validated();
        $dto = AddressDTO::fromArray(array_merge($addressModel->toArray(), $data, ['id' => $address]));
        $this->addressService->updateAddress($address, $dto);

        return response()->json([
            'message' => 'Endereço atualizado com sucesso',
            'address' => $this->addressService->getAddressById($address)
        ], 200);
    }
}
