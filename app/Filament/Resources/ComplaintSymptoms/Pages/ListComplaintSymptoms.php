<?php

namespace App\Filament\Resources\ComplaintSymptoms\Pages;

use App\Filament\Resources\ComplaintSymptoms\ComplaintSymptomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComplaintSymptoms extends ListRecords
{
    protected static string $resource = ComplaintSymptomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
