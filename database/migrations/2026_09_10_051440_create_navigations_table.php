<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            
            // Hierarki Sub-menu (Parent-Child)
            $table->foreignId('parent_id')->nullable()->constrained('navigations')->nullOnDelete();
            
            // Flexible Routing (Bisa internal page atau external link)
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('url')->nullable(); // Diisi jika page_id kosong (misal: 'https://google.com')
            $table->string('target')->default('_self'); // '_self' atau '_blank' (buka tab baru)
            
            // Display Control
            $table->integer('order')->default(0)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};