<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Navigation;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Halaman Beranda & Profil
        $home = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Selamat Datang di Website Kami',
                'content' => '<p>Ini adalah halaman utama statis yang dikelola langsung dari Admin Panel Filament.</p>',
                'is_published' => true,
            ]
        );

        $about = Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Perusahaan',
                'content' => '<p>Kami adalah perusahaan yang bergerak di bidang solusi teknologi informasi.</p>',
                'is_published' => true,
            ]
        );

        // 2. Navigasi
        Navigation::firstOrCreate(
            ['label' => 'Beranda', 'page_id' => $home->id],
            ['order' => 1]
        );

        Navigation::firstOrCreate(
            ['label' => 'Tentang Kami', 'page_id' => $about->id],
            ['order' => 2]
        );
    }
}