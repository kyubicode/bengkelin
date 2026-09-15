<?php

namespace App\Filament\Resources\TechnicianTasks\Tables;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TechnicianTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue_number')
                    ->label('No. Antrian')
                    ->state(fn ($record) => $record->queue_number ?? '-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->description(fn ($record) => Carbon::parse($record->booking_time)->format('H:i') . ' WIB'),

                TextColumn::make('license_plate')
                    ->label('No. Kendaraan')
                    ->description(fn ($record) => "{$record->vehicle_type} - {$record->transmission}")
                    ->weight('bold'),

                TextColumn::make('services.name')
                    ->label('Jenis Servis')
                    ->badge()
                    ->separator(',')
                    ->limitList(2)
                    ->expandableLimitedList(),

                // ⬇️ DIPERBAIKI: symptoms.name -> symptoms.symptom_name
                TextColumn::make('symptoms.symptom_name')
                    ->label('Keluhan')
                    ->badge()
                    ->color('danger')
                    ->separator(',')
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->placeholder('Tidak ada keluhan'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'confirmed' => 'Menunggu Dikerjakan',
                        'processing' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'confirmed' => 'Menunggu Dikerjakan',
                        'processing' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                    ]),

                // ⬇️ BARU: filter cepat tugas hari ini
                Filter::make('today')
                    ->label('Jadwal Hari Ini')
                    ->query(fn (Builder $query): Builder => $query->whereDate('booking_date', today()))
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Detail'),

                Action::make('startWork')
                    ->label('Mulai')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'confirmed')
                    // ⬇️ BARU: disable tombol kalau teknisi masih punya job lain yang berjalan
                    ->disabled(fn ($record) => Booking::technicianHasActiveJob($record->technician_id, $record->id))
                    ->tooltip(fn ($record) => Booking::technicianHasActiveJob($record->technician_id, $record->id)
                        ? 'Selesaikan pekerjaan yang sedang berjalan terlebih dahulu'
                        : null)
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        // ⬇️ BARU: validasi ulang di server, jangan cuma andalkan disable di UI
                        if (Booking::technicianHasActiveJob($record->technician_id, $record->id)) {
                            Notification::make()
                                ->title('Tidak bisa memulai pekerjaan')
                                ->body('Anda masih memiliki pekerjaan lain yang sedang berjalan. Selesaikan itu dulu.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'status' => 'processing',
                            'started_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Pekerjaan dimulai')
                            ->success()
                            ->send();
                    }),

                Action::make('finishWork')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Pekerjaan selesai')
                            ->success()
                            ->send();
                    }),

                // ⬇️ BARU: tambah/edit catatan pekerjaan
                Action::make('addNote')
                    ->label('Catatan')
                    ->icon('heroicon-o-pencil-square')
                    ->color('gray')
                    ->visible(fn ($record) => in_array($record->status, ['processing', 'completed']))
                    ->form([
                        Textarea::make('notes')
                            ->label('Catatan Pekerjaan')
                            ->rows(4)
                            ->default(fn ($record) => $record->notes)
                            ->placeholder('Tuliskan progress, kendala, atau sparepart yang dipakai...'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update(['notes' => $data['notes']]);

                        Notification::make()
                            ->title('Catatan tersimpan')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('booking_date')
            ->paginated([10, 25, 50]);
    }
}