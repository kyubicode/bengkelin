<?php

namespace App\Filament\Resources\Galleries\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->stacked()
                    ->limit(3),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewUrls')
                    ->label('URL')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->modalHeading('Image URLs')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('2xl')
                    ->schema(fn ($record) => self::urlModalSchema($record)),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Schema untuk modal "View URL": kartu-kartu sejajar dalam
     * satu baris (wrap otomatis), tiap kartu berisi gambar di atas
     * dan input URL + tombol copy di bawahnya.
     */
    protected static function urlModalSchema($record): array
    {
        $paths = collect($record->image_path ?? []);

        if ($paths->isEmpty()) {
            return [
                Placeholder::make('empty')
                    ->hiddenLabel()
                    ->content('Tidak ada gambar.'),
            ];
        }

        $cards = $paths->map(function (string $path, int $index) {
            $url = Storage::disk('public')->url($path);

            return Group::make([
                ViewField::make("preview_{$index}")
                    ->hiddenLabel()
                    ->view('filament.forms.components.image-preview-thumb', [
                        'url' => $url,
                    ]),

                TextInput::make("url_{$index}")
                    ->hiddenLabel()
                    ->default($url)
                    ->readOnly()
                    ->copyable(
                        copyMessage: 'URL disalin!',
                        copyMessageDuration: 1500,
                    ),
            ]);
        })->toArray();

        return [
            Grid::make(6)
                ->schema($cards),
        ];
    }
}