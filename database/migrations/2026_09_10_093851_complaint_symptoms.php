<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Contoh: AC, Mesin, Kaki-kaki
            $table->string('symptom_name'); // Contoh: Pipa AC beku / ada bunga es
            $table->text('assessment_question')->nullable(); // Pertanyaan triage awal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_symptoms');
    }
};