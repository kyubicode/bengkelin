<?php

namespace App\Filament\Resources\TechnicianTasks\Pages;

use App\Filament\Resources\TechnicianTasks\TechnicianTaskResource;
use Filament\Resources\Pages\ListRecords;

class ListTechnicianTasks extends ListRecords
{
    protected static string $resource = TechnicianTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}