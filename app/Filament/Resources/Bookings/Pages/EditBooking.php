<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\Service;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $duration = Service::whereIn('id', $data['services'] ?? [])->sum('duration_minutes') ?: 60;

        $scheduleChanged = ($data['technician_id'] ?? null) != $this->record->technician_id
            || $data['booking_date'] != $this->record->booking_date
            || $data['booking_time'] != $this->record->booking_time;

        if ($scheduleChanged && ! empty($data['technician_id'])) {
            $available = Booking::isTechnicianAvailable(
                $data['technician_id'],
                $data['booking_date'],
                $data['booking_time'],
                $duration,
                ignoreBookingId: $this->record->id
            );

            if (! $available) {
                Notification::make()
                    ->title('Teknisi sudah terisi di jadwal ini')
                    ->body('Silakan pilih teknisi lain atau ubah jadwal.')
                    ->danger()
                    ->send();

                $this->halt();
            }
        }

        $data['end_time'] = Booking::calculateEndTime(
            $data['booking_date'],
            $data['booking_time'],
            $duration
        );

        // Auto-sync timestamp saat status berubah
        $oldStatus = $this->record->status;
        $newStatus = $data['status'];

        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'confirmed' && ! $this->record->assigned_at) {
                $data['assigned_at'] = now();
            }
            if ($newStatus === 'processing' && ! $this->record->started_at) {
                $data['started_at'] = now();
            }
            if ($newStatus === 'completed' && ! $this->record->completed_at) {
                $data['completed_at'] = now();
            }
        }

        return $data;
    }
}