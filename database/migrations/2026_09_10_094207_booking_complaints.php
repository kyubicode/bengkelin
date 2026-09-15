<?php
//Pivot Booking <-> Keluhan & Jawaban Assessment)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('complaint_symptom_id')->constrained('complaint_symptoms')->cascadeOnDelete();
            $table->text('assessment_answer')->nullable(); // Menyimpan jawaban pilihan user (Ya/Tidak/Tidak tahu)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_complaints');
    }
};