<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'role_id'  => $adminRole?->id ?? 1,
                'name'     => 'Admin CMS',
                'password' => Hash::make('password'),
            ]
        );
    }
}