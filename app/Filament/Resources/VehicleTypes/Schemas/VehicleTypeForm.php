<?php

namespace App\Filament\Resources\VehicleTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Tipe Kendaraan')
                    ->placeholder('Contoh: SUV, Sedan, City Car')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Urutan Tampil')
                    ->helperText('Angka lebih kecil tampil lebih dulu di form booking.')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Jika nonaktif, opsi ini tidak muncul di form booking pelanggan.')
                    ->default(true)
                    ->inline(false),
            ]);
    }
}