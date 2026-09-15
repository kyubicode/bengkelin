<?php

namespace App\Filament\Resources\Navigations;

use App\Filament\Concerns\HasNavigationPermission;
use App\Filament\Resources\Navigations\Pages\CreateNavigation;
use App\Filament\Resources\Navigations\Pages\EditNavigation;
use App\Filament\Resources\Navigations\Pages\ListNavigations;
use App\Filament\Resources\Navigations\Schemas\NavigationForm;
use App\Filament\Resources\Navigations\Tables\NavigationsTable;
use App\Models\Navigation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NavigationResource extends Resource
{
     use HasNavigationPermission; 
    protected static ?string $model = Navigation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Master CMS';

    public static function form(Schema $schema): Schema
    {
        return NavigationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NavigationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNavigations::route('/'),
            'create' => CreateNavigation::route('/create'),
            'edit' => EditNavigation::route('/{record}/edit'),
        ];
    }
}
