<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Permission;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPermissionIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = app(UserService::class);
    }

    public function test_assign_permission_to_user()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'edit_user']);

        $result = $this->userService->assignPermission($user->id, $permission->id);

        $this->assertTrue($result);
        $this->assertTrue($user->permissions()->where('permission_id', $permission->id)->exists());
    }

    public function test_get_user_permissions()
    {
        $user = User::factory()->create();

        $permission1 = Permission::create(['name' => 'create_user']);
        $permission2 = Permission::create(['name' => 'delete_user']);

        $user->permissions()->attach([$permission1->id, $permission2->id]);

        $permissions = $this->userService->getUserPermissions($user->id);

        $this->assertCount(2, $permissions);
    }

    public function test_remove_permission_from_user()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'view_user']);

        $user->permissions()->attach($permission->id);

        $this->assertTrue($user->permissions()->where('permission_id', $permission->id)->exists());

        $result = $this->userService->removePermission($user->id, $permission->id);

        $this->assertTrue($result);
        $this->assertFalse($user->permissions()->where('permission_id', $permission->id)->exists());
    }

    public function test_user_without_permissions_returns_empty()
    {
        $user = User::factory()->create();

        $permissions = $this->userService->getUserPermissions($user->id);

        $this->assertCount(0, $permissions);
    }
}
