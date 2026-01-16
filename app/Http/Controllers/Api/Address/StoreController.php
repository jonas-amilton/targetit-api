<?php

namespace App\Http\Controllers\Api\Address;

use App\DTOs\AddressDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    /**
     * Store a newly created address
     */
    public function __invoke(StoreAddressRequest $request, int $user): JsonResponse
    {
        $dto = AddressDTO::fromArray(array_merge($request->validated(), ['user_id' => $user]));
        $address = $this->addressService->createAddress($dto);

        return response()->json([
            'message' => 'Endereço criado com sucesso',
            'address' => $address
        ], 201);
    }
}
