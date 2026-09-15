<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\Service;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $duration = Service::whereIn('id', $data['services'] ?? [])->sum('duration_minutes') ?: 60;

        $available = Booking::isTechnicianAvailable(
            $data['technician_id'],
            $data['booking_date'],
            $data['booking_time'],
            $duration
        );

        if (! $available) {
            Notification::make()
                ->title('Teknisi sudah terisi di jadwal ini')
                ->body('Silakan pilih teknisi lain atau ubah jadwal.')
                ->danger()
                ->send();

            $this->halt();
        }

        // booking_code belum ada di form (disabled saat create), generate di sini
        $data['booking_code'] = $this->generateUniqueBookingCode();

        $data['end_time'] = Booking::calculateEndTime(
            $data['booking_date'],
            $data['booking_time'],
            $duration
        );

        $data['assigned_at'] = now();

        return $data;
    }

    private function generateUniqueBookingCode(): string
    {
        do {
            $code = 'BKG-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}