<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class DestroyController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Delete the specified user
     */
    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'Usuário deletado com sucesso'
        ], 200);
    }
}
