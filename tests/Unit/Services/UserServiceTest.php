<?php

namespace Tests\Unit\Services;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Foundation\Testing\TestCase;

class UserServiceTest extends TestCase
{
    private $userService;
    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function test_get_all_users()
    {
        $users = \Mockery::mock(\Illuminate\Pagination\LengthAwarePaginator::class);

        $this->userRepository->shouldReceive('paginate')
            ->once()
            ->andReturn($users);

        $result = $this->userService->getAllUsers();

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    }

    public function test_get_user_by_id_found()
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'cpf' => '12345678901',
            'phone' => '11999999999',
        ]);

        $this->userRepository->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($user);

        $result = $this->userService->getUserById(1);

        $this->assertEquals($user->id, $result->id);
        $this->assertEquals($user->email, $result->email);
    }

    public function test_get_user_by_id_not_found()
    {
        $this->userRepository->shouldReceive('findById')
            ->with(999)
            ->once()
            ->andReturn(null);

        $result = $this->userService->getUserById(999);

        $this->assertNull($result);
    }

    public function test_create_user()
    {
        $dto = new UserDTO(
            name: 'New User',
            email: 'newuser@example.com',
            cpf: '12345678901',
            phone: '11999999999',
            password: 'password123'
        );

        $user = new User([
            'id' => 1,
            'name' => $dto->name,
            'email' => $dto->email,
            'cpf' => $dto->cpf,
            'phone' => $dto->phone,
        ]);

        $this->userRepository->shouldReceive('create')
            ->with($dto)
            ->once()
            ->andReturn($user);

        $result = $this->userService->createUser($dto);

        $this->assertEquals($user->email, $result->email);
    }

    public function test_delete_user()
    {
        $this->userRepository->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(true);

        $result = $this->userService->deleteUser(1);

        $this->assertTrue($result);
    }

    public function test_assign_permission_user_not_found()
    {
        $this->userRepository->shouldReceive('findById')
            ->with(999)
            ->once()
            ->andReturn(null);

        $result = $this->userService->assignPermission(999, 1);

        $this->assertFalse($result);
    }
}
