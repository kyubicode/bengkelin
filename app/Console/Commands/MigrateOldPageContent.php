<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
//migrasi data ke JSON format
class MigrateOldPageContent extends Command
{
    protected $signature = 'pages:migrate-content';
    protected $description = 'Bungkus konten HTML lama jadi block rich_text';

    public function handle(): void
    {
        Page::query()->cursor()->each(function (Page $page) {
            $raw = $page->getRawOriginal('content');

            if (is_array(json_decode($raw, true))) {
                $this->line("Skip (sudah JSON): {$page->slug}");
                return;
            }

            $page->content = [
                [
                    'type' => 'rich_text',
                    'data' => ['body' => $raw ?? ''],
                ],
            ];
            $page->saveQuietly();

            $this->info("Migrated: {$page->slug}");
        });

        $this->info('Selesai.');
    }
}