<?php

namespace App\Filament\Resources\PageResource\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->label('Judul'),
                TextColumn::make('slug')->searchable()->label('Slug'),
                TextColumn::make('module_type')->badge()->placeholder('Statis')->label('Modul'),
                ToggleColumn::make('is_published')->label('Tampil di Navigasi'),
                TextColumn::make('updated_at')->dateTime()->sortable()->label('Terakhir Diubah'),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}