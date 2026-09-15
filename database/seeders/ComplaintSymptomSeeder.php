<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintSymptomSeeder extends Seeder
{
    /**
     * Data diambil & dirapikan dari file:
     * List_daftar_keluhan-Booking1.xlsx (sheet: Sheet1)
     * Kolom 'assessment_question' tidak ada di file sumber,
     * sehingga diisi null (nullable) - bisa diisi manual belakangan.
     */
    public function run(): void
    {
        $data = [
            // AC
            ['category' => 'AC', 'symptom_name' => 'Pipa AC beku/ Ada bunga es', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Magnet Clutch tidak berputar/ mati', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Kompresor AC bersuara kasar', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Selang AC basah oli', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Saat hidup AC, temperatur naik', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'AC hidup tidak otomatis', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'AC dingin sebentar lalu panas', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'AC hanya dingin saat kendaraan berjalan', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'AC tidak dingin saat idle', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Hembusan AC kurang kencang', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Hembusan AC tidak keluar', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'AC tidak dapat menyala', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Sirkulasi udara AC kurang baik', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Kaca mudah berembun saat AC menyala', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Kompresor AC tidak bekerja', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Terasa ada getaran saat AC dinyalakan', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Terasa beban mesin berat saat AC dinyalakan', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Ada kebocoran freon', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Freon cepat habis', 'assessment_question' => null],
            ['category' => 'AC', 'symptom_name' => 'Ingin pengecekan dan perawatan AC', 'assessment_question' => null],

            // MESIN
            ['category' => 'MESIN', 'symptom_name' => 'Mesin Susah Hidup', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Mesin Brebet', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Mesin Cepat Panas', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Mesin Bergetar', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Tenaga Terasa Kurang', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Suara mesin Tidak Normal', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Asap Knalpot Pedih', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Knalpot Keluar Asap Putih', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Knalpot Keluar Asap Hitam', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Mesin Hidup Nembak-nebak', 'assessment_question' => null],
            ['category' => 'MESIN', 'symptom_name' => 'Saat mesin hidup dan injak gas, Ngempos', 'assessment_question' => null],

            // KAKI-KAKI
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Stir terasa tidak normal', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Bunyi tidak normal pada kaki-kaki', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Steer terasa berat', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Ban terasa tidak normal', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Ban bergelombang', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Bunyi aneh pada saat belok patah', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Rack steer keluar oli', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Steer ngebuang saat jalan tidak rata', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Putaran Steer tidak normal', 'assessment_question' => null],
            ['category' => 'KAKI-KAKI', 'symptom_name' => 'Putaran steer cenderung ke salah satu sisi', 'assessment_question' => null],

            // KELISTRIKAN
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Volt aki berkurang dari kapasitas nya', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Lampu tidak menyala', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Central Lock Bermasalah', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Dinamo Starter bermasalah', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Power window bermasalah', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Indikator Dahboard menyala', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Lampu rem menyala terus menerus', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Dinamo Ampere bermasalah', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Alternator bermasalah', 'assessment_question' => null],
            ['category' => 'KELISTRIKAN', 'symptom_name' => 'Motor wiper bermasalah', 'assessment_question' => null],

            // REM
            ['category' => 'REM', 'symptom_name' => 'Ada bunyi di komponen pengereman', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Pedal Rem bergetar', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Lampu indikator rem menyala', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Rem Kurang pakem', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Pedal rem terlalu dalam', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Roda terasa ngebuang pada satu sisi saat di injak rem', 'assessment_question' => null],
            ['category' => 'REM', 'symptom_name' => 'Minyak rem sering berkurang', 'assessment_question' => null],

            // TRANSMISI (KONVENSIONAL)
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Perpindahan gigi terasa kasar', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Transmisi selip', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Ada kebocoran oli transmisi', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Gigi sulit masuk', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Ada bunyi di bagian transmisi', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Injakan pedal kopling dalam', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Minyak koplin sering berkurang', 'assessment_question' => null],
            ['category' => 'TRANSMISI (KONVENSIONAL)', 'symptom_name' => 'Saat Macet bau gososng', 'assessment_question' => null],

            // TRANSMISI (METIK)
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Indikator AT mengedip', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Indikator D mengedip', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Masuk R terdapat hentakan', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Masuk R tidak langsung respon mundur', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Masuk D terdapat hentakan', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Tarikan delay', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Perpindahan gigi, harus gas tinggi', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Ada kebocoran oli di bagian transmisi (metik)', 'assessment_question' => null],
            ['category' => 'TRANSMISI (METIK)', 'symptom_name' => 'Suara metik kasar', 'assessment_question' => null],

        ];

        foreach ($data as &$row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('complaint_symptoms')->insert($data);
    }
}