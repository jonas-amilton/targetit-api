<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    /**
     * Get all users
     */
    public function getAllUsers(): LengthAwarePaginator
    {
        return $this->userRepository->paginate();
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user
     */
    public function createUser(UserDTO $dto): User
    {
        return $this->userRepository->create($dto);
    }

    /**
     * Update a user with partial data
     */
    public function updateUserPartial(int $id, array $data): bool
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return false;
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $user->update($data);
    }

    /**
     * Update a user
     */
    public function updateUser(int $id, UserDTO $dto): bool
    {
        return $this->userRepository->update($id, $dto);
    }

    /**
     * Delete a user (soft delete)
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * Restore a soft deleted user
     */
    public function restoreUser(int $id): bool
    {
        return $this->userRepository->restore($id);
    }

    /**
     * Assign permission to user
     */
    public function assignPermission(int $userId, int $permissionId): bool
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return false;
        }

        $user->permissions()->attach($permissionId);
        return true;
    }

    /**
     * Remove permission from user
     */
    public function removePermission(int $userId, int $permissionId): bool
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return false;
        }

        $user->permissions()->detach($permissionId);
        return true;
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions(int $userId): ?Collection
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return null;
        }

        return $user->permissions;
    }
}

