<?php

namespace App\Filament\Resources\TechnicianTasks;

use App\Filament\Concerns\HasNavigationPermission;
use App\Filament\Resources\TechnicianTasks\Pages\ListTechnicianTasks;
use App\Filament\Resources\TechnicianTasks\Pages\ViewTechnicianTask;
use App\Filament\Resources\TechnicianTasks\Schemas\TechnicianTaskForm;
use App\Filament\Resources\TechnicianTasks\Schemas\TechnicianTaskInfolist;
use App\Filament\Resources\TechnicianTasks\Tables\TechnicianTasksTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TechnicianTaskResource extends Resource
{
    use HasNavigationPermission;

    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Tugas Saya';

    protected static ?string $modelLabel = 'Tugas';

    protected static ?string $pluralModelLabel = 'Tugas Saya';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Layanan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('technician_id', auth()->id())
            // ⬇️ BARU: batasi hanya status yang relevan untuk teknisi
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->orderBy('created_at');
    }

    public static function form(Schema $schema): Schema
    {
        return TechnicianTaskForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TechnicianTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TechnicianTasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTechnicianTasks::route('/'),
            'view' => ViewTechnicianTask::route('/{record}'),
        ];
    }
}