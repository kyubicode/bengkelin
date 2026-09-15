<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Parameter 1: Kunci pencarian
            [                              // Parameter 2: Data yang disimpan
                'role_id'  => 1,
                'name'     => 'Admin CMS',
                'password' => Hash::make('password'),
            ]
        );
    }
}