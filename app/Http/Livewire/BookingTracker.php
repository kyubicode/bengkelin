<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Livewire\Component;

class BookingTracker extends Component
{
    public string $bookingCode = '';

    public ?Booking $booking = null;

    public bool $searched = false;

    protected $rules = [
        'bookingCode' => 'required|string|min:3',
    ];

    protected $messages = [
        'bookingCode.required' => 'Kode booking wajib diisi.',
    ];

    public function search(): void
    {
        $this->validate();

        $this->searched = true;

        $this->booking = Booking::query()
            ->with(['services', 'symptoms'])
            ->where('booking_code', trim($this->bookingCode))
            ->first();
    }

    public function resetSearch(): void
    {
        $this->reset(['bookingCode', 'booking', 'searched']);
    }

    /**
     * Bangun timeline status untuk ditampilkan di UI.
     */
    public function getStatusStepsProperty(): array
    {
        if (! $this->booking) {
            return [];
        }

        if ($this->booking->status === 'cancelled') {
            return [
                ['key' => 'cancelled', 'label' => 'Dibatalkan', 'done' => true, 'active' => true, 'cancelled' => true],
            ];
        }

        $steps = [
            'pending'    => 'Menunggu Konfirmasi',
            'confirmed'  => 'Dikonfirmasi',
            'processing' => 'Sedang Dikerjakan',
            'completed'  => 'Selesai',
        ];

        $keys = array_keys($steps);
        $currentIndex = array_search($this->booking->status, $keys);
        $currentIndex = $currentIndex === false ? 0 : $currentIndex;

        $result = [];
        $i = 0;
        foreach ($steps as $key => $label) {
            $result[] = [
                'key'    => $key,
                'label'  => $label,
                'done'   => $i <= $currentIndex,
                'active' => $i === $currentIndex,
            ];
            $i++;
        }

        return $result;
    }

    public function render()
    {
         return view('components.booking-tracker');
    }
}