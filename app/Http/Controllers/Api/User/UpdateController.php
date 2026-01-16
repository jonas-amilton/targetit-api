<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Update the specified user
     */
    public function __invoke(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $this->userService->updateUserPartial($id, $request->validated());

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $this->userService->getUserById($id)
        ], 200);
    }
}
