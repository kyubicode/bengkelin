<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // Label ditampilkan, mis: "Admin", "Teknisi"
            $table->string('slug')->unique(); // Dipakai di kode, mis: "admin", "teknisi"
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();
        });

        // Seed 2 role dasar supaya sistem langsung bisa dipakai tanpa
        // admin harus buat manual dulu sebelum ada yang bisa login sebagai apa-apa.
        DB::table('roles')->insert([
            ['name' => 'Admin', 'slug' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teknisi', 'slug' => 'teknisi', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });

        Schema::dropIfExists('roles');
    }
};