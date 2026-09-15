<?php

namespace App\Filament\Resources\Assignments\Pages;

use App\Filament\Resources\Assignments\AssignmentResource;
use Filament\Resources\Pages\ListRecords;

class ListAssignments extends ListRecords
{
    protected static string $resource = AssignmentResource::class;

    // ⬇️ TAMBAHAN: override judul halaman secara eksplisit
    protected static ?string $title = 'Daftar Status Tugas Teknisi';

    protected function getHeaderActions(): array
    {
        return [];
    }
}