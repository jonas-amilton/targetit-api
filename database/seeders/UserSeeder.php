<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed users to the database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'cpf' => '11111111111',
                'phone' => '11999999999',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'usuario@example.com'],
            [
                'name' => 'Usuário Teste',
                'cpf' => '22222222222',
                'phone' => '11988888888',
                'password' => bcrypt('password'),
            ]
        );
    }
}
