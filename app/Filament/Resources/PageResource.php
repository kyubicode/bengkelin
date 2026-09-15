<?php

namespace App\Filament\Resources;


use App\Filament\Concerns\HasNavigationPermission;// mengatur hak akses
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;

class PageResource extends Resource
{
    use HasNavigationPermission; 
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Manajemen Halaman';
    protected static ?string $pluralModelLabel = 'Halaman';
    protected static ?string $modelLabel = 'Halaman';

    protected static string|\UnitEnum|null $navigationGroup = 'Master CMS';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state)))
                    ->label('Judul Halaman'),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->label('Slug URL'),

                Forms\Components\Select::make('module_type')
                    ->options([
                        'home' => 'Home',
                        'about' => 'About',
                        'contact' => 'Contact',
                        'booking_tracker' => 'Cek Status Booking',
                        'booking' => 'Form Booking',
                        'custom' => 'Custom',
                        'gallery' => 'Galeri Kegiatan',
                    ])
                    ->label('Tipe Modul'),

Builder::make('content')
    ->label('Konten Halaman')
    ->columnSpanFull()
    ->collapsible()
    ->blocks([

        Block::make('rich_text')
            ->label('Teks / Artikel')
            ->icon('heroicon-o-document-text')
            ->schema([
                RichEditor::make('body')->label('Isi'),
            ]),

        Block::make('hero')
            ->label('Hero (Statis)')
            ->icon('heroicon-o-photo')
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()->disk('public')->directory('hero-backgrounds')
                    ->label('Gambar Latar'),
                Forms\Components\TextInput::make('heading')->label('Judul'),
                Forms\Components\TextInput::make('subheading')->label('Sub-judul'),
                Forms\Components\TextInput::make('button_text')->label('Teks Tombol'),
                Forms\Components\TextInput::make('button_url')->label('Link Tombol'),
            ]),

        Block::make('hero_slider')
            ->label('Hero Slider (Gambar Berjalan)')
            ->icon('heroicon-o-photo')
            ->schema([
                Repeater::make('slides')
                    ->label('Slide')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()->disk('public')->directory('hero-slides')
                            ->required(),
                        Forms\Components\TextInput::make('heading'),
                        Forms\Components\TextInput::make('subheading'),
                        Forms\Components\TextInput::make('button_text'),
                        Forms\Components\TextInput::make('button_url'),
                    ])
                    ->columns(2)
                    ->reorderable()
                    ->collapsible(),
                Forms\Components\TextInput::make('interval')
                    ->numeric()->default(5000)
                    ->label('Jeda antar slide (ms)'),
            ]),

    Block::make('card_grid')
    ->label('Grid Kartu (Layanan/Fitur/dll)')
    ->icon('heroicon-o-squares-2x2')
    ->schema([
        Forms\Components\TextInput::make('title')
            ->label('Judul Section')
            ->default('Layanan Kami'),

        Forms\Components\TextInput::make('subtitle')
            ->label('Sub-judul Section')
            ->default('Solusi lengkap untuk perawatan kendaraan Anda'),

        Repeater::make('items')
            ->label('Daftar Kartu')
            ->schema([
                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->disk('public')
                    ->directory('card-icons')
                    ->label('Ikon')
                    ->helperText('Upload ikon (svg/png, disarankan transparan)'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul Kartu'),

                Forms\Components\Textarea::make('description')
                    ->rows(2)
                    ->label('Deskripsi Singkat'),
            ])
            ->columns(1)
            ->reorderable()
            ->collapsible()
            ->columnSpanFull(),
    ]),    

        Block::make('gallery_grid')
            ->label('Galeri')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                Forms\Components\Select::make('gallery_id')
                    ->relationship('gallery', 'title')
                    ->searchable()->preload()
                    ->label('Pilih Galeri'),
            ]),

        Block::make('cta')
            ->label('Call To Action')
            ->icon('heroicon-o-megaphone')
            ->schema([
                Forms\Components\TextInput::make('label')->label('Teks Tombol'),
                Forms\Components\TextInput::make('url')->label('Link'),
            ]),

    ]),
//---
                Forms\Components\Toggle::make('is_published')
                    ->required()
                    ->default(true)
                    ->label('Publikasikan di Navigasi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul'),

                TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),

                TextColumn::make('module_type')
                    ->badge()
                    ->placeholder('Statis')
                    ->label('Modul'),

                ToggleColumn::make('is_published')
                    ->label('Tampil di Navigasi'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Terakhir Diubah'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}