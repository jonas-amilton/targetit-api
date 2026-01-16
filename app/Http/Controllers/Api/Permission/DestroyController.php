<?php

namespace App\Http\Controllers\Api\Permission;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class DestroyController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Destroy (Remove) a permission from a user
     */
    public function __invoke(int $user, int $permission): JsonResponse
    {
        $success = $this->userService->removePermission($user, $permission);

        if (!$success) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Permissão removida com sucesso'
        ], 200);
    }
}
