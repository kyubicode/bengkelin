<?php

namespace App\Filament\Resources\Navigations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NavigationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required(),
                Select::make('page_id')
                    ->relationship('page', 'title')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Tampilkan Menu')
                    ->helperText('Nonaktifkan untuk menyembunyikan menu ini dari navigasi tanpa menghapusnya')
                    ->default(true),
            ]);
    }
}