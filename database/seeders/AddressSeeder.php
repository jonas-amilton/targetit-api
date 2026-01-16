<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed addresses to the database.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if ($admin) {
            Address::firstOrCreate(
                ['user_id' => $admin->id, 'cep' => '01310100'],
                [
                    'street' => 'Avenida Paulista',
                    'number' => '1000',
                    'district' => 'Bela Vista',
                    'complement' => 'Apt 1001',
                ]
            );
        }
    }
}
