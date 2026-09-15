<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Images')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('galleries')
                    ->visibility('public')
                    ->panelLayout('grid')
                    ->imagePreviewHeight('100')
                    ->reorderable()
                    ->columns(8)
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('category')
                    ->maxLength(255),

                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}