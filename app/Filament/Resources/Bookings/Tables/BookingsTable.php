<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\VehicleType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode booking disalin')
                    ->weight('bold'),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->customer_phone),

                TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->description(fn ($record) => Carbon::parse($record->booking_time)->format('H:i') . ' WIB')
                    ->sortable(),

                // ⬇️ BARU: kolom teknisi
                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->badge()
                    ->color(fn ($record) => $record->technician_id ? 'success' : 'danger')
                    ->default('⚠️ Belum ditugaskan')
                    ->sortable(),

                // ⬇️ BARU: nomor antrian (accessor dari Booking model)
                TextColumn::make('queue_number')
                    ->label('No. Antrian')
                    ->state(fn ($record) => $record->queue_number ?? '-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'processing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),

                SelectFilter::make('service_method')
                    ->label('Metode Servis')
                    ->options([
                        'workshop' => 'Datang ke Bengkel',
                        'home_service' => 'Home Service',
                        'pickup' => 'Jemput Kendaraan',
                    ]),

                SelectFilter::make('vehicle_type')
                    ->label('Tipe Kendaraan')
                    ->options(fn () => VehicleType::orderBy('sort_order')->pluck('name', 'name')),

                SelectFilter::make('services')
                    ->label('Layanan')
                    ->relationship('services', 'name')
                    ->multiple()
                    ->preload(),

                // ⬇️ BARU: filter per teknisi
                SelectFilter::make('technician_id')
                    ->label('Teknisi')
                    ->relationship('technician', 'name', fn (Builder $query) => $query->technicians())
                    ->preload(),

                // ⬇️ BARU: filter booking yang belum ada teknisi
                Filter::make('unassigned')
                    ->label('Belum Ada Teknisi')
                    ->query(fn (Builder $query): Builder => $query->whereNull('technician_id'))
                    ->toggle(),

                Filter::make('booking_date')
                    ->label('Rentang Jadwal Booking')
                    ->schema([
                        DatePicker::make('booking_date_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('booking_date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['booking_date_from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['booking_date_until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('booking_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['booking_date_from'] ?? null) {
                            $indicators['booking_date_from'] = 'Jadwal dari ' . Carbon::parse($data['booking_date_from'])->format('d M Y');
                        }

                        if ($data['booking_date_until'] ?? null) {
                            $indicators['booking_date_until'] = 'Jadwal sampai ' . Carbon::parse($data['booking_date_until'])->format('d M Y');
                        }

                        return $indicators;
                    }),

                Filter::make('today_only')
                    ->label('Jadwal Hari Ini')
                    ->query(fn (Builder $query): Builder => $query->whereDate('booking_date', today()))
                    ->toggle(),

                TernaryFilter::make('needs_address')
                    ->label('Perlu Alamat Layanan')
                    ->placeholder('Semua metode')
                    ->trueLabel('Home Service / Jemput saja')
                    ->falseLabel('Datang ke Bengkel saja')
                    ->queries(
                        true: fn (Builder $query) => $query->whereIn('service_method', ['home_service', 'pickup']),
                        false: fn (Builder $query) => $query->where('service_method', 'workshop'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}