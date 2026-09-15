<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Konversi data lama (HTML string) jadi JSON valid,
        //    selama kolom masih longText — supaya ALTER di step berikutnya tidak gagal.
        DB::statement("
            UPDATE pages
            SET content = JSON_ARRAY(
                JSON_OBJECT('type', 'rich_text', 'data', JSON_OBJECT('body', COALESCE(content, '')))
            )
            WHERE content IS NULL OR JSON_VALID(content) = 0
        ");

        // 2. Baru sekarang aman ubah tipe kolom jadi json
        Schema::table('pages', function (Blueprint $table) {
            $table->json('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('content')->nullable()->change();
        });
    }
};