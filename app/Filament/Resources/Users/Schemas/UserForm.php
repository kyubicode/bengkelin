<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                // Dropdown pilih Role -- ini yang tidak ter-generate otomatis
                // karena role_id adalah foreign key ke tabel lain.
                Select::make('role_id')
                    ->label('Role')
                    ->options(fn () => Role::pluck('name', 'id'))
                    ->required()
                    ->native(false)
                    ->searchable(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    // Wajib diisi saat create user baru, tapi opsional saat edit
                    // (kalau dikosongkan berarti password lama tidak diubah).
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->maxLength(255),
            ]);
    }
}