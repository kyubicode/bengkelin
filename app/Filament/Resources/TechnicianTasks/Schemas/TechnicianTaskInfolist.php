<?php

namespace App\Filament\Resources\TechnicianTasks\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TechnicianTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Booking')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('booking_code')->label('Kode Booking')->weight('bold'),

                            // ⬇️ BARU
                            TextEntry::make('queue_number')
                                ->label('No. Antrian')
                                ->state(fn ($record) => $record->queue_number ?? '-')
                                ->badge(),

                            TextEntry::make('status')
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
                        ]),
                ]),

            Section::make('Data Kendaraan & Pelanggan')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('customer_name')->label('Pelanggan'),
                            TextEntry::make('customer_phone')->label('No. HP')->copyable(),
                            TextEntry::make('license_plate')->label('No. Polisi'),
                            TextEntry::make('vehicle_type')->label('Tipe Kendaraan'),
                            TextEntry::make('transmission')->label('Transmisi'),
                        ]),
                ]),

            Section::make('Jenis Servis')
                ->schema([
                    TextEntry::make('services.name')
                        ->label('Layanan')
                        ->badge(),
                ]),

            Section::make('Keluhan Pelanggan')
                ->schema([
                    RepeatableEntry::make('symptoms')
                        ->hiddenLabel()
                        ->schema([
                            // ⬇️ DIPERBAIKI: name -> symptom_name
                            TextEntry::make('symptom_name')
                                ->label('Keluhan')
                                ->weight('bold'),

                            TextEntry::make('category')
                                ->label('Kategori')
                                ->badge(),

                            TextEntry::make('pivot.assessment_answer')
                                ->label('Jawaban Assessment')
                                ->placeholder('Tidak ada jawaban tambahan')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->visible(fn ($record) => $record->symptoms->isNotEmpty()),

            Section::make('Alamat / Metode Servis')
                ->schema([
                    TextEntry::make('service_method')
                        ->label('Metode')
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'workshop' => 'Datang ke Bengkel',
                            'home_service' => 'Home Service',
                            'pickup' => 'Jemput Kendaraan',
                            default => ucfirst($state),
                        }),

                    TextEntry::make('service_address')
                        ->label('Alamat')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),

            // ⬇️ BARU: tampilkan catatan pekerjaan yang sudah diisi teknisi
            Section::make('Catatan Pekerjaan')
                ->schema([
                    TextEntry::make('notes')
                        ->hiddenLabel()
                        ->placeholder('Belum ada catatan'),
                ])
                ->visible(fn ($record) => in_array($record->status, ['processing', 'completed'])),

            // ⬇️ BARU: timeline waktu pengerjaan
            Section::make('Waktu Pengerjaan')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('assigned_at')->label('Ditugaskan')->dateTime('d M Y H:i')->placeholder('-'),
                        TextEntry::make('started_at')->label('Mulai Dikerjakan')->dateTime('d M Y H:i')->placeholder('-'),
                        TextEntry::make('completed_at')->label('Selesai')->dateTime('d M Y H:i')->placeholder('-'),
                    ]),
                ]),
        ]);
    }
}