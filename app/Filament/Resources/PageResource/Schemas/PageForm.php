<?php

namespace App\Filament\Resources\PageResource\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('module_type')
                    ->options([
                        'home' => 'Home',
                        'about' => 'About',
                        'contact' => 'Contact',
                        'booking' => 'Form Booking',
                        'custom' => 'Custom',
                    ])
                    ->label('Tipe Modul'),

                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull()
                    ->label('Konten Utama'),

                Forms\Components\Toggle::make('is_published')
                    ->required()
                    ->label('Publikasikan'),
            ]);
    }
}