<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Publikasi & Informasi';
    protected static ?string $navigationLabel = 'Galeri Kegiatan';
    protected static ?string $modelLabel = 'Galeri Kegiatan';
    protected static ?string $pluralModelLabel = 'Dokumentasi Kegiatan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dokumentasi Kegiatan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('judul_kegiatan')
                            ->label('Nama / Judul Kegiatan')
                            ->placeholder('Contoh: Upacara Hari Kemerdekaan / Gelar Karya P5')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('tanggal_kegiatan')
                            ->label('Tanggal Pelaksanaan')
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Singkat Acara')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Unggah Foto-foto Kegiatan (Bisa pilih banyak foto sekaligus)')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->directory('galleries')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kegiatan')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('foto')
                    ->label('Jumlah Foto')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' Foto' : '0 Foto')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('tanggal_kegiatan', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
