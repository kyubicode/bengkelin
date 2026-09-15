<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Contoh: BKG-20260910-001
            
            // Tahap 01: Kendaraan
            $table->string('vehicle_type'); // SUV, Sedan, City Car, dll
            $table->string('transmission'); // Automatic / Manual
            $table->string('vehicle_brand')->nullable(); // Honda, Toyota, dll
            $table->string('vehicle_model')->nullable(); // Jazz, Avanza, dll
            $table->integer('vehicle_year')->nullable();
            
            // Tahap 05 & 06: Metode & Jadwal Servis
            $table->string('service_method'); // workshop (Datang), home_service, pickup (Jemput)
            $table->date('booking_date');
            $table->time('booking_time');
            
            // Tahap 07: Data Pelanggan
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('license_plate'); // No. Polisi
            $table->text('service_address')->nullable(); // Alamat (wajib jika home_service/pickup)
            
            // Status & Catatan
            $table->string('status')->default('pending'); // pending, confirmed, processing, completed, cancelled
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};