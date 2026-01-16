<?php

namespace App\Http\Controllers\Api\User;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Store a newly created user
     */
    public function __invoke(StoreUserRequest $request): JsonResponse
    {
        $dto = UserDTO::fromArray($request->validated());
        $user = $this->userService->createUser($dto);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user
        ], 201);
    }
}
