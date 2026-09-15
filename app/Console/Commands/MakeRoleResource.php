<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
//tempel trait otomatis ketika generate resource comand: php artisan make:role-resource Service --generate
class MakeRoleResource extends Command
{
    protected $signature = 'make:role-resource
                            {name : Nama model/resource, contoh: Service}
                            {--generate : Teruskan flag --generate ke make:filament-resource}';

    protected $description = 'Generate Filament Resource baru, otomatis pasang trait akses menu, dan sync daftar permission';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));

        $this->info("Membuat Filament Resource: {$name}Resource ...");

        $exitCode = Artisan::call('make:filament-resource', [
            'name' => $name,
            '--generate' => $this->option('generate'),
        ]);

        if ($exitCode !== 0) {
            $this->error('Gagal membuat Filament Resource. Proses dihentikan.');
            return self::FAILURE;
        }

        $this->line(Artisan::output());

        $resourceFile = $this->findResourceFile($name);

        if (! $resourceFile) {
            $this->warn("File {$name}Resource.php tidak ditemukan otomatis. Tempelkan trait HasNavigationPermission secara manual.");
            return self::SUCCESS;
        }

        $this->attachTrait($resourceFile, $name);

        $this->info('Menyinkronkan daftar permission menu ...');
        Artisan::call('nav-permissions:generate');
        $this->line(Artisan::output());

        $this->info("Selesai! {$name}Resource sudah siap dan otomatis kena aturan akses role.");
        $this->comment("Jangan lupa buka /admin/roles untuk atur permission menu ini ke role yang perlu.");

        return self::SUCCESS;
    }

    protected function findResourceFile(string $name): ?string
    {
        $resourcePath = app_path('Filament/Resources');
        $targetFilename = "{$name}Resource.php";

        foreach (File::allFiles($resourcePath) as $file) {
            if ($file->getFilename() === $targetFilename) {
                return $file->getPathname();
            }
        }

        return null;
    }

    protected function attachTrait(string $filePath, string $name): void
    {
        $content = File::get($filePath);

        // 1. Tambahkan use import kalau belum ada
        if (! str_contains($content, 'use App\Filament\Concerns\HasNavigationPermission;')) {
            $content = preg_replace(
                '/^(namespace .+;\s*\n)/m',
                "$1\nuse App\\Filament\\Concerns\\HasNavigationPermission;\n",
                $content,
                1
            );
        }

        // 2. Tambahkan "use HasNavigationPermission;" di dalam body class kalau belum ada
        if (! preg_match('/class\s+' . preg_quote($name, '/') . 'Resource[^\{]*\{\s*\n\s*use HasNavigationPermission;/', $content)) {
            $content = preg_replace(
                '/(class\s+' . preg_quote($name, '/') . 'Resource[^\{]*\{\s*\n)/',
                "$1    use HasNavigationPermission;\n\n",
                $content,
                1
            );
        }

        File::put($filePath, $content);
        $this->info("Trait HasNavigationPermission berhasil ditempel ke {$filePath}");
    }
}