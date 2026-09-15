<?php

namespace App\Filament\Resources\TechnicianTasks\Pages;

use App\Filament\Resources\TechnicianTasks\TechnicianTaskResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTechnicianTask extends ViewRecord
{
    protected static string $resource = TechnicianTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('startWork')
                ->label('Mulai Kerjakan')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->visible(fn () => $this->record->status === 'confirmed')
                // ⬇️ BARU
                ->disabled(fn () => Booking::technicianHasActiveJob($this->record->technician_id, $this->record->id))
                ->tooltip(fn () => Booking::technicianHasActiveJob($this->record->technician_id, $this->record->id)
                    ? 'Selesaikan pekerjaan yang sedang berjalan terlebih dahulu'
                    : null)
                ->requiresConfirmation()
                ->modalDescription('Status booking akan berubah menjadi "Sedang Dikerjakan".')
                ->action(function (): void {
                    // ⬇️ BARU: validasi ulang di server
                    if (Booking::technicianHasActiveJob($this->record->technician_id, $this->record->id)) {
                        Notification::make()
                            ->title('Tidak bisa memulai pekerjaan')
                            ->body('Anda masih memiliki pekerjaan lain yang sedang berjalan.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $this->record->update([
                        'status' => 'processing',
                        'started_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Pekerjaan dimulai')
                        ->success()
                        ->send();

                    $this->redirect(TechnicianTaskResource::getUrl('view', ['record' => $this->record]));
                }),

            Action::make('finishWork')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === 'processing')
                ->requiresConfirmation()
                ->modalDescription('Status booking akan berubah menjadi "Selesai".')
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Pekerjaan selesai')
                        ->success()
                        ->send();

                    $this->redirect(TechnicianTaskResource::getUrl('view', ['record' => $this->record]));
                }),

            // ⬇️ BARU: tambah catatan langsung dari halaman detail
            Action::make('addNote')
                ->label('Tambah Catatan')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->visible(fn () => in_array($this->record->status, ['processing', 'completed']))
                ->form([
                    Textarea::make('notes')
                        ->label('Catatan Pekerjaan')
                        ->rows(4)
                        ->default(fn () => $this->record->notes)
                        ->placeholder('Tuliskan progress, kendala, atau sparepart yang dipakai...'),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['notes' => $data['notes']]);

                    Notification::make()
                        ->title('Catatan tersimpan')
                        ->success()
                        ->send();

                    $this->redirect(TechnicianTaskResource::getUrl('view', ['record' => $this->record]));
                }),
        ];
    }
}