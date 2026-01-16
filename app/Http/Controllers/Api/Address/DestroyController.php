<?php

namespace App\Http\Controllers\Api\Address;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class DestroyController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    /**
     * Delete the specified address
     */
    public function __invoke(int $user, int $address): JsonResponse
    {
        $addressModel = $this->addressService->getAddressById($address);

        if (!$addressModel || $addressModel->user_id !== $user) {
            return response()->json([
                'message' => 'Endereço não encontrado'
            ], 404);
        }

        $this->addressService->deleteAddress($address);

        return response()->json([
            'message' => 'Endereço deletado com sucesso'
        ], 200);
    }
}
