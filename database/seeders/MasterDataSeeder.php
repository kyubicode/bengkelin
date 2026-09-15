<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ComplaintSymptom;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Layanan (Tahap 02)
        $services = [
            ['name' => 'Ganti Oli & Filter', 'description' => 'Penggantian oli mesin dan filter secara berkala.'],
            ['name' => 'Tune Up Mesin', 'description' => 'Pembersihan komponen mesin, busi, dan injektor.'],
            ['name' => 'Servis AC Berkala', 'description' => 'Pengecekan freon, filter kabin, dan kebersihan blower.'],
            ['name' => 'Kaki-kaki & Suspensi', 'description' => 'Pemeriksaan shockbreaker, bushing, dan tie rod.'],
            ['name' => 'Pengecekan Rem', 'description' => 'Pembersihan kampas rem dan penggantian minyak rem.'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 2. Seed Gejala & Pertanyaan Assessment (Tahap 03 & 04)
        $symptoms = [
            // Kategori AC
            [
                'category' => 'AC',
                'symptom_name' => 'AC Kurang Dingin / Tidak Dingin Sama Sekali',
                'assessment_question' => 'Apakah hembusan angin tetap kencang tapi hawanya tidak dingin?'
            ],
            [
                'category' => 'AC',
                'symptom_name' => 'Muncul Bau Apek atau Bau Gosong saat AC Nyala',
                'assessment_question' => 'Apakah bau tersebut langsung tercium sesaat setelah tombol AC dinyalakan?'
            ],
            // Kategori Mesin
            [
                'category' => 'Mesin',
                'symptom_name' => 'Mesin Bergetar / Ngadat saat Idle (Stasioner)',
                'assessment_question' => 'Apakah getaran terasa lebih parah saat AC mobil dihidupkan?'
            ],
            [
                'category' => 'Mesin',
                'symptom_name' => 'Keluar Asap Putih/Hitam dari Knalpot',
                'assessment_question' => 'Apakah asap muncul terus-menerus atau hanya saat mobil pertama kali dipanaskan di pagi hari?'
            ],
            // Kategori Kaki-kaki
            [
                'category' => 'Kaki-kaki',
                'symptom_name' => 'Terdengar Bunyi "Bletak" atau "Grojok" saat Lewat Polisi Tidur',
                'assessment_question' => 'Apakah bunyi tersebut berasal dari bagian depan kendaraan?'
            ]
        ];

        foreach ($symptoms as $symptom) {
            ComplaintSymptom::create($symptom);
        }
    }
}