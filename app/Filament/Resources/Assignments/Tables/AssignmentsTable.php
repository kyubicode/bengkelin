<?php

namespace App\Filament\Resources\Assignments\Tables;

use App\Models\Booking;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class AssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable(),

                TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->description(fn ($record) => Carbon::parse($record->booking_time)->format('H:i') . ' WIB'),

                // ⬇️ BARU: nomor antrian
                TextColumn::make('queue_number')
                    ->label('No. Antrian')
                    ->state(fn ($record) => $record->queue_number ?? '-')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->description(fn ($record) => $record->license_plate)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'confirmed' => 'Menunggu Assign',
                        'processing' => 'Dikerjakan',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->placeholder('Belum ditugaskan')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('assigned_at')
                    ->label('Waktu Assign')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'confirmed' => 'Menunggu Konfirmasi Teknisi',
                        'processing' => 'Dikerjakan',
                        'completed' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                Action::make('assignTechnician')
                    ->label(fn ($record) => $record->technician_id ? 'Ganti Teknisi' : 'Assign')
                    ->icon('heroicon-o-user-plus')
                    ->color(fn ($record) => $record->technician_id ? 'gray' : 'primary')
                    ->visible(fn ($record) => $record->status === 'confirmed')
                    ->form(function ($record) {
                        $duration = Service::whereIn(
                            'id',
                            $record->services()->pluck('services.id')
                        )->sum('duration_minutes') ?: 60;

                        $technicians = User::technicians()->orderBy('name')->get();

                        $closedTechnicianIds = [];
                        $options = [];

                        foreach ($technicians as $tech) {
                            $isAvailable = Booking::isTechnicianAvailable(
                                $tech->id,
                                $record->booking_date->format('Y-m-d'),
                                $record->booking_time,
                                $duration,
                                ignoreBookingId: $record->id
                            );

                            if ($isAvailable) {
                                $options[$tech->id] = "✅ {$tech->name} — Tersedia";
                            } else {
                                $options[$tech->id] = "🔴 {$tech->name} — Bentrok Jadwal";
                                $closedTechnicianIds[] = $tech->id;
                            }
                        }

                        return [
                            Select::make('technician_id')
                                ->label('Pilih Teknisi')
                                ->options($options)
                                ->disableOptionWhen(fn (string $value): bool => in_array((int) $value, $closedTechnicianIds))
                                ->searchable()
                                ->required()
                                ->default(fn () => $record->technician_id)
                                ->helperText('Teknisi dengan tanda 🔴 sedang bentrok jadwal dan tidak bisa dipilih.'),
                        ];
                    })
                    ->action(function ($record, array $data): void {
                        $duration = Service::whereIn(
                            'id',
                            $record->services()->pluck('services.id')
                        )->sum('duration_minutes') ?: 60;

                        $available = Booking::isTechnicianAvailable(
                            $data['technician_id'],
                            $record->booking_date->format('Y-m-d'),
                            $record->booking_time,
                            $duration,
                            ignoreBookingId: $record->id
                        );

                        if (! $available) {
                            Notification::make()
                                ->title('Gagal menugaskan teknisi')
                                ->body('Teknisi ini sudah bentrok jadwal pada slot waktu tersebut.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'technician_id' => $data['technician_id'],
                            'assigned_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Teknisi berhasil ditugaskan')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('booking_date')
            ->paginated([10, 25, 50]);
    }
}