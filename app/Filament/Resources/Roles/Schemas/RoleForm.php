<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Support\NavigationPermissions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama role')
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Dipakai di kode, contoh: admin, teknisi')
                    ->required()
                    ->unique(ignoreRecord: true),

                CheckboxList::make('permissions')
                    ->label('Menu yang bisa diakses')
                    ->options(NavigationPermissions::all())
                    ->columns(2)
                    ->searchable(),
            ]);
    }
}