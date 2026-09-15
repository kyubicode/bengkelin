<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ComplaintSymptom;
use App\Models\VehicleType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingWizard extends Component
{
    public $currentStep = 1;
    protected $totalSteps = 9;

    // ... (semua property lama tetap sama, tidak diubah) ...

    // Tahap 01 - Kendaraan
    public $vehicle_type = '';
    public $transmission = '';
    public $brand = '';
    public $model_name = '';
    public $year = '';

    public $selected_services = [];

    public $active_category = '';
    public $selected_symptoms = [];
    public $assessment_answers = [];

    public $service_method = '';

    public $booking_date = '';
    public $booking_time = '';

    public $customer_name = '';
    public $customer_phone = '';
    public $license_plate = '';
    public $service_address = '';

    public $booking_code = '';

    // ⬇️ TAMBAHAN: cache jumlah teknisi tersedia, dihitung ulang tiap ganti tanggal/jam
    public ?int $availableTechnicianCount = null;

    public function toggleCategory(string $category): void
    {
        $this->active_category = $this->active_category === $category ? '' : $category;
    }

    public function updatedSelectedSymptoms(): void
    {
        $this->assessment_answers = collect($this->assessment_answers)
            ->only($this->selected_symptoms)
            ->toArray();
    }

    // ⬇️ TAMBAHAN: setiap kali tanggal/jam berubah, cek ulang ketersediaan
    public function updatedBookingDate(): void
    {
        $this->checkAvailability();
    }

    public function updatedBookingTime(): void
    {
        $this->checkAvailability();
    }

    /**
     * Hitung total durasi (menit) dari semua layanan yang dipilih.
     * Default 60 menit kalau belum ada layanan dipilih / durasi tidak diset.
     */
    private function getServiceDuration(): int
    {
        if (empty($this->selected_services)) {
            return 60;
        }

        return Service::whereIn('id', $this->selected_services)->sum('duration_minutes') ?: 60;
    }

    /**
     * Cek jumlah teknisi yang kosong pada tanggal & jam yang dipilih customer.
     * Hasil disimpan ke $availableTechnicianCount untuk ditampilkan live di Blade.
     */
    public function checkAvailability(): void
    {
        $this->resetErrorBag('booking_time');

        if (! $this->booking_date || ! $this->booking_time) {
            $this->availableTechnicianCount = null;
            return;
        }

        $duration = $this->getServiceDuration();

        $this->availableTechnicianCount = Booking::getAvailableTechnicians(
            $this->booking_date,
            $this->booking_time,
            $duration
        )->count();
    }

    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        $this->currentStep = min($this->totalSteps, $this->currentStep + 1);
    }

    public function previousStep()
    {
        $this->currentStep = max(1, $this->currentStep - 1);
    }

    public function validateStep(int $step)
    {
        if ($step == 1) {
            $this->validate([
                'vehicle_type' => [
                    'required',
                    'string',
                    Rule::exists('vehicle_types', 'name')->where('is_active', true),
                ],
                'transmission' => 'required|string',
                'brand' => 'nullable|string|max:100',
                'model_name' => 'nullable|string|max:100',
                'year' => 'nullable|integer|min:1980|max:' . (date('Y') + 1),
            ]);
        } elseif ($step == 2) {
            $this->validate([
                'selected_services' => 'required|array|min:1',
                'selected_services.*' => 'exists:services,id',
            ]);
        } elseif ($step == 3) {
            $this->validate([
                'selected_symptoms' => 'nullable|array',
                'selected_symptoms.*' => 'exists:complaint_symptoms,id',
            ]);
        } elseif ($step == 4) {
            $this->validate([
                'assessment_answers' => 'nullable|array',
                'assessment_answers.*' => 'nullable|string|max:2000',
            ]);
        } elseif ($step == 5) {
            $this->validate([
                'service_method' => 'required|in:workshop,home_service,pickup',
            ]);
        } elseif ($step == 6) {
            $this->validate([
                'booking_date' => 'required|date|after_or_equal:today',
                'booking_time' => 'required',
            ]);

            // ⬇️ TAMBAHAN: cek ketersediaan teknisi sebelum lanjut ke step berikutnya
            $this->checkAvailability();

            if ($this->availableTechnicianCount < 1) {
                $this->addError('booking_time', 'Maaf, semua teknisi sudah penuh pada jadwal ini. Silakan pilih tanggal atau jam lain.');

                throw ValidationException::withMessages([
                    'booking_time' => 'Maaf, semua teknisi sudah penuh pada jadwal ini. Silakan pilih tanggal atau jam lain.',
                ]);
            }
        } elseif ($step == 7) {
            $this->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'license_plate' => 'required|string|max:20',
                'service_address' => $this->service_method !== 'workshop'
                    ? 'required|string|max:500'
                    : 'nullable|string|max:500',
            ]);
        }
    }

    public function validateCurrentStep()
    {
        $this->validateStep($this->currentStep);
    }

    public function submitBooking()
    {
        for ($step = 1; $step <= 7; $step++) {
            $this->validateStep($step);
        }

        try {
            $booking = DB::transaction(function () {
                $duration = $this->getServiceDuration();

                // ⬇️ PENTING: lock baris booking di tanggal yang sama supaya
                // dua customer yang submit BERSAMAAN di slot yang sama
                // tidak bisa dua-duanya lolos cek ketersediaan.
                DB::table('bookings')
                    ->whereDate('booking_date', $this->booking_date)
                    ->lockForUpdate()
                    ->get();

                $technician = Booking::getAvailableTechnicians(
                    $this->booking_date,
                    $this->booking_time,
                    $duration
                )->first();

                if (! $technician) {
                    // Slot baru saja terisi orang lain tepat sebelum lock diambil
                    throw ValidationException::withMessages([
                        'booking_time' => 'Maaf, slot ini baru saja terisi customer lain. Silakan pilih jadwal lain.',
                    ]);
                }

                $bookingCode = $this->generateUniqueBookingCode();

                $booking = Booking::create([
                    'booking_code'    => $bookingCode,
                    'customer_name'   => $this->customer_name,
                    'customer_phone'  => $this->customer_phone,
                    'license_plate'   => strtoupper(preg_replace('/\s+/', ' ', trim($this->license_plate))),
                    'vehicle_type'    => $this->vehicle_type,
                    'transmission'    => $this->transmission,
                    'vehicle_brand'   => $this->brand ?: null,
                    'vehicle_model'   => $this->model_name ?: null,
                    'vehicle_year'    => $this->year ?: null,
                    'service_method'  => $this->service_method,
                    'booking_date'    => $this->booking_date,
                    'booking_time'    => $this->booking_time,
                    'end_time'        => Booking::calculateEndTime($this->booking_date, $this->booking_time, $duration),
                    'service_address' => $this->service_address ?: null,
                    'status'          => 'pending',
                    'technician_id'   => $technician->id,
                    'assigned_at'     => now(),
                ]);

                $booking->services()->sync($this->selected_services);

                if (!empty($this->selected_symptoms)) {
                    $syncData = [];
                    foreach ($this->selected_symptoms as $symptomId) {
                        $syncData[$symptomId] = [
                            'assessment_answer' => $this->assessment_answers[$symptomId] ?? null,
                        ];
                    }
                    $booking->symptoms()->sync($syncData);
                }

                return $booking;
            });
        } catch (ValidationException $e) {
            // Kalau ternyata gagal karena slot keburu penuh saat proses transaksi,
            // lempar customer balik ke step jadwal biar bisa pilih ulang.
            $this->currentStep = 6;
            $this->checkAvailability();
            throw $e;
        }

        $this->booking_code = $booking->booking_code;
        $this->currentStep = 9;
    }

    private function generateUniqueBookingCode(): string
    {
        do {
            $code = 'BKG-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    public function getSelectedServiceNamesProperty()
    {
        if (empty($this->selected_services)) {
            return collect();
        }

        return Service::whereIn('id', $this->selected_services)->pluck('name');
    }

    public function getSelectedSymptomsDetailProperty()
    {
        if (empty($this->selected_symptoms)) {
            return collect();
        }

        return ComplaintSymptom::whereIn('id', $this->selected_symptoms)
            ->orderBy('category')
            ->orderBy('symptom_name')
            ->get();
    }

    public function getSelectedCountByCategoryProperty()
    {
        return $this->selectedSymptomsDetail->groupBy('category')->map->count();
    }

public function render()
{
    $symptoms = ComplaintSymptom::orderBy('category')
        ->orderBy('symptom_name')
        ->get();

    $categories = $symptoms
        ->pluck('category')
        ->filter()
        ->map(fn ($category) => trim($category))
        ->unique(fn ($category) => strtolower($category))
        ->values();

    return view('components.booking-wizard', [
        'vehicleTypesList' => VehicleType::where('is_active', true)
            ->orderBy('sort_order')
            ->get(),

        'servicesList' => Service::where('is_active', true)
            ->get(),

        'complaintCategories' => $categories,

        'symptomsList' => $symptoms,
    ]);
}
}