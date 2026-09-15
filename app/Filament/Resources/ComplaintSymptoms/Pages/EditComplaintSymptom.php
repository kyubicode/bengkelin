<?php

namespace App\Filament\Resources\ComplaintSymptoms\Pages;

use App\Filament\Resources\ComplaintSymptoms\ComplaintSymptomResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComplaintSymptom extends EditRecord
{
    protected static string $resource = ComplaintSymptomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
