<?php

namespace App\Http\Controllers\Api\Permission;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Store (Assign) a permission to a user
     */
    public function __invoke(Request $request, int $user): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:permissions,id'
        ]);

        $success = $this->userService->assignPermission($user, $request->id);

        if (!$success) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Permissão atribuída com sucesso'
        ], 201);
    }
}
