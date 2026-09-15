<?php

namespace App\Filament\Resources\Navigations\Pages;

use App\Filament\Resources\Navigations\NavigationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNavigation extends EditRecord
{
    protected static string $resource = NavigationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
