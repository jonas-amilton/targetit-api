<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Display a listing of users
     */
    public function __invoke(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users, 200);
    }
}
