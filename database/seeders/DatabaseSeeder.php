<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seeder User & CMS Filament
            UserSeeder::class,
            CmsSeeder::class,

            // Seeder Master Data Booking Bengkel (Layanan & Keluhan)
            MasterDataSeeder::class,
        ]);
    }
}