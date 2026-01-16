<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed permissions to the database.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create_user']);
        Permission::firstOrCreate(['name' => 'edit_user']);
        Permission::firstOrCreate(['name' => 'delete_user']);
        Permission::firstOrCreate(['name' => 'view_user']);
    }
}
