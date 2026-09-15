<?php

namespace App\Filament\Resources\ComplaintSymptoms;

use App\Filament\Concerns\HasNavigationPermission;
use App\Filament\Resources\ComplaintSymptoms\Pages\CreateComplaintSymptom;
use App\Filament\Resources\ComplaintSymptoms\Pages\EditComplaintSymptom;
use App\Filament\Resources\ComplaintSymptoms\Pages\ListComplaintSymptoms;
use App\Models\ComplaintSymptom;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ComplaintSymptomResource extends Resource
{
     use HasNavigationPermission; 
    protected static ?string $model = ComplaintSymptom::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

   // Ubah menjadi seperti ini (tambahkan \UnitEnum):
    protected static string|\UnitEnum|null $navigationGroup = 'Master Layanan';
    
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->placeholder('Contoh: AC, Mesin, Kaki-kaki'),
                Forms\Components\TextInput::make('symptom_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('assessment_question')
                    ->label('Pertanyaan Triage/Assessment')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')->badge()->sortable(),
                Tables\Columns\TextColumn::make('symptom_name')->searchable(),
                Tables\Columns\TextColumn::make('assessment_question')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListComplaintSymptoms::route('/'),
            'create' => CreateComplaintSymptom::route('/create'),
            'edit' => EditComplaintSymptom::route('/{record}/edit'),
        ];
    }
}