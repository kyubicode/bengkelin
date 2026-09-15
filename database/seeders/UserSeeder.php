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
            ['role_id'=>1],
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin CMS',
                'password' => Hash::make('password'),
            ]
        );
    }
}