<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Booking')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('booking_code')
                                ->label('Kode Booking')
                                ->copyable()
                                ->weight('bold'),

                            TextEntry::make('status')
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
                                }),

                            // ⬇️ BARU
                            TextEntry::make('queue_number')
                                ->label('No. Antrian')
                                ->state(fn ($record) => $record->queue_number ?? '-')
                                ->badge()
                                ->color('gray'),
                        ]),
                ]),

            Section::make('Data Pelanggan')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('customer_name')
                                ->label('Nama Pelanggan'),

                            TextEntry::make('customer_phone')
                                ->label('No. HP')
                                ->copyable(),
                        ]),
                ]),

            Section::make('Data Kendaraan')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('license_plate')
                                ->label('No. Polisi')
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('vehicle_type')
                                ->label('Tipe Kendaraan'),

                            TextEntry::make('transmission')
                                ->label('Transmisi'),
                        ]),
                ]),

            // ⬇️ BARU: daftar layanan yang diambil
            Section::make('Layanan')
                ->schema([
                    TextEntry::make('services.name')
                        ->label('')
                        ->badge()
                        ->separator(','),
                ]),

            // ⬇️ DIGANTI: gabung jadwal + teknisi jadi satu section
            Section::make('Jadwal & Teknisi')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('booking_date')
                                ->label('Tanggal')
                                ->date('d M Y'),

                            TextEntry::make('booking_time')
                                ->label('Jam')
                                ->time('H:i'),

                            TextEntry::make('end_time')
                                ->label('Estimasi Selesai')
                                ->dateTime('d M Y H:i')
                                ->placeholder('-'),

                            TextEntry::make('technician.name')
                                ->label('Teknisi Ditugaskan')
                                ->badge()
                                ->color(fn ($record) => $record->technician_id ? 'success' : 'danger')
                                ->default('⚠️ Belum ada teknisi'),

                            TextEntry::make('assigned_at')
                                ->label('Waktu Assign')
                                ->dateTime('d M Y H:i')
                                ->placeholder('-'),

                            TextEntry::make('service_method')
                                ->label('Metode')
                                ->badge()
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'workshop' => 'Datang ke Bengkel',
                                    'home_service' => 'Home Service',
                                    'pickup' => 'Jemput Kendaraan',
                                    default => ucfirst(str_replace('_', ' ', $state)),
                                })
                                ->color(fn (string $state): string => match ($state) {
                                    'workshop' => 'gray',
                                    'home_service' => 'info',
                                    'pickup' => 'warning',
                                    default => 'gray',
                                }),
                        ]),
                ])
                ->columnSpanFull(),

            Section::make('Alamat Layanan')
                ->schema([
                    TextEntry::make('service_address')
                        ->hiddenLabel()
                        ->placeholder('Tidak ada alamat (datang ke bengkel)')
                        ->columnSpanFull(),
                ])
                ->visible(fn ($record) => in_array($record->service_method, ['home_service', 'pickup'])),

            // ⬇️ BARU: keluhan & jawaban assessment
            Section::make('Keluhan & Assessment')
                ->schema([
                    RepeatableEntry::make('symptoms')
                        ->label('')
                        ->schema([
                            TextEntry::make('symptom_name')->label('Gejala')->weight('bold'),
                            TextEntry::make('category')->label('Kategori')->badge(),
                            TextEntry::make('pivot.assessment_answer')
                                ->label('Jawaban')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->visible(fn ($record) => $record->symptoms->isNotEmpty()),

            // ⬇️ BARU: catatan internal
            Section::make('Catatan Internal')
                ->schema([
                    TextEntry::make('notes')
                        ->hiddenLabel()
                        ->placeholder('Tidak ada catatan'),
                ])
                ->visible(fn ($record) => ! empty($record->notes)),
        ]);
    }
}