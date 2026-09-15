<?php

namespace App\Filament\Resources\Assignments;

use App\Filament\Concerns\HasNavigationPermission;
use App\Filament\Resources\Assignments\Pages\ListAssignments;
use App\Filament\Resources\Assignments\Schemas\AssignmentForm;
use App\Filament\Resources\Assignments\Tables\AssignmentsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssignmentResource extends Resource
{
    use HasNavigationPermission;

    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    // ⬇️ DIUBAH: label navigasi sidebar
    protected static ?string $navigationLabel = 'Status Tugas Teknisi';

    protected static ?string $modelLabel = 'Tugas Teknisi';

    // ⬇️ DIUBAH: label plural (dipakai di judul halaman index)
    protected static ?string $pluralModelLabel = 'Daftar Status Tugas Teknisi';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Layanan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->orderBy('booking_date')
            ->orderBy('booking_time');
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignments::route('/'),
        ];
    }
}