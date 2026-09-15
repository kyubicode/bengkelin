<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['slug' => Role::ADMIN],
            [
                'name' => 'Administrator',
                'permissions' => ['bookings', 'services', 'users', 'roles'], // Sesuaikan permission jika ada
            ]
        );

        Role::firstOrCreate(
            ['slug' => Role::TEKNISI],
            [
                'name' => 'Teknisi',
                'permissions' => ['bookings'],
            ]
        );
    }
}