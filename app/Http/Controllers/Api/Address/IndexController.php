<?php

namespace App\Http\Controllers\Api\Address;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    /**
     * Display addresses for a specific user
     */
    public function __invoke(int $user): JsonResponse
    {
        $addresses = $this->addressService->getAddressesByUserId($user);
        return response()->json($addresses, 200);
    }
}
