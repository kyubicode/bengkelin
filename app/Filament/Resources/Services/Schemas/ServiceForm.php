<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                Grid::make(2)->schema([
                    TextInput::make('duration_minutes')
                        ->label('Estimasi Durasi')
                        ->numeric()
                        ->required()
                        ->default(60)
                        ->minValue(1)
                        ->suffix('menit')
                        ->helperText('Dipakai sistem untuk menghitung ketersediaan teknisi saat booking.'),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->helperText('Nonaktifkan untuk sembunyikan dari pilihan customer tanpa menghapus datanya.'),
                ]),
            ]);
    }
}