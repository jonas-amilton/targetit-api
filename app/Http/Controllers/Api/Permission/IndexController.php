<?php

namespace App\Http\Controllers\Api\Permission;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * List user permissions
     */
    public function __invoke(int $user): JsonResponse
    {
        $permissions = $this->userService->getUserPermissions($user);

        if ($permissions === null) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        return response()->json($permissions, 200);
    }
}
