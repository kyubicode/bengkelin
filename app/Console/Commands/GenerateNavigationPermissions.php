<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
//jalankan perintah untuk update halaman  php artisan nav-permissions:generate
class GenerateNavigationPermissions extends Command
{
    protected $signature = 'nav-permissions:generate';
    protected $description = 'Scan semua Filament Resource lalu generate app/Support/NavigationPermissions.php';

    public function handle(): int
    {
        $resourcePath = app_path('Filament/Resources');

        if (! File::isDirectory($resourcePath)) {
            $this->error('Folder app/Filament/Resources tidak ditemukan.');
            return self::FAILURE;
        }

        $entries = [];

        // scan rekursif karena tiap Resource ada di subfoldernya sendiri
        // contoh: Resources/Bookings/BookingResource.php
        foreach (File::allFiles($resourcePath) as $file) {
            if (! str_ends_with($file->getFilename(), 'Resource.php')) {
                continue;
            }

            $className = str_replace('Resource.php', '', $file->getFilename());
            $key = Str::snake(Str::plural($className));
            $label = Str::headline($className);

            $entries[$key] = $label;
        }

        ksort($entries);

        if (empty($entries)) {
            $this->warn('Tidak ada Resource ditemukan.');
            return self::SUCCESS;
        }

        $lines = collect($entries)
            ->map(fn ($label, $key) => "            '{$key}' => '{$label}',")
            ->implode("\n");

        $stub = <<<PHP
        <?php

        namespace App\Support;

        class NavigationPermissions
        {
            /**
             * File ini digenerate otomatis oleh:
             *   php artisan nav-permissions:generate
             *
             * Label boleh diedit manual, tapi jangan ubah key-nya kalau sudah
             * dipakai di data role yang ada di database.
             */
            public static function all(): array
            {
                return [
        {$lines}
                ];
            }
        }
        PHP;

        File::ensureDirectoryExists(app_path('Support'));
        File::put(app_path('Support/NavigationPermissions.php'), $stub);

        $this->info('NavigationPermissions.php berhasil digenerate dengan ' . count($entries) . ' menu:');
        foreach ($entries as $key => $label) {
            $this->line("  - {$key} => {$label}");
        }

        return self::SUCCESS;
    }
}