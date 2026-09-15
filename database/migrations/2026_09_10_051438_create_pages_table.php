<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            
            // Dispatcher Modul (ditambah index untuk query cepat)
            $table->string('module_type')->nullable()->index();
            $table->json('settings')->nullable();
            
            // SEO & Optimization
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Status & Safety
            $table->boolean('is_published')->default(true)->index();
            $table->softDeletes(); // Fitur restore jika terhapus tidak sengaja
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};