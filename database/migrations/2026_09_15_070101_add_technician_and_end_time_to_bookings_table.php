<?php
// database/migrations/2026_09_15_070101_add_technician_and_end_time_to_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // technician_id DIHAPUS karena sudah ada
            $table->dateTime('end_time')->nullable()->after('booking_time');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('end_time');
        });
    }
};