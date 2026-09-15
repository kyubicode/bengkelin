<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Booking;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Booking')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('booking_code')
                                ->label('Kode Booking')
                                ->disabled()
                                ->dehydrated(false)
                                ->visibleOn('edit'),

                            Select::make('status')
                                ->options([
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'processing' => 'Diproses',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),
                    ]),

                Section::make('Data Pelanggan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('customer_name')->label('Nama Pelanggan')->required(),
                            TextInput::make('customer_phone')->label('No. HP')->required(),
                        ]),
                    ]),

                Section::make('Data Kendaraan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('license_plate')->label('No. Polisi')->required(),
                            TextInput::make('vehicle_type')->label('Tipe Kendaraan')->required(),
                            TextInput::make('transmission')->label('Transmisi')->required(),
                            TextInput::make('vehicle_brand')->label('Merk'),
                            TextInput::make('vehicle_model')->label('Model'),
                            TextInput::make('vehicle_year')->label('Tahun')->numeric(),
                        ]),
                    ]),

                Section::make('Layanan')
                    ->schema([
                        Select::make('services')
                            ->label('Layanan')
                            ->relationship('services', 'name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('technician_id', null))
                            ->columnSpanFull(),
                    ]),

                Section::make('Jadwal & Teknisi')
                    ->schema([
                        Grid::make(3)->schema([
                            DatePicker::make('booking_date')
                                ->label('Tanggal')
                                ->required()
                                ->native(false)
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('technician_id', null)),

                            TimePicker::make('booking_time')
                                ->label('Jam')
                                ->required()
                                ->seconds(false)
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('technician_id', null)),

                            Select::make('service_method')
                                ->label('Metode Servis')
                                ->options([
                                    'workshop' => 'Datang ke Bengkel',
                                    'home_service' => 'Home Service',
                                    'pickup' => 'Jemput Kendaraan',
                                ])
                                ->required(),
                        ]),

                        Select::make('technician_id')
                            ->label('Teknisi')
                            ->options(function (callable $get) {
                                $date = $get('booking_date');
                                $time = $get('booking_time');
                                $serviceIds = $get('services') ?? [];

                                if (! $date || ! $time) {
                                    return [];
                                }

                                $duration = Service::whereIn('id', $serviceIds)->sum('duration_minutes') ?: 60;

                                // Saat edit, teknisi yang sedang ditugaskan ke booking ini
                                // harus tetap muncul di opsi meski slotnya "penuh" olehnya sendiri.
                                $bookingId = $get('id');

                                return Booking::getAvailableTechnicians($date, $time, $duration, $bookingId)
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->native(false)
                            ->helperText('Pilih tanggal, jam & layanan dulu — hanya teknisi kosong yang tampil.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Alamat & Catatan')
                    ->schema([
                        Textarea::make('service_address')
                            ->label('Alamat Layanan')
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan Internal')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}