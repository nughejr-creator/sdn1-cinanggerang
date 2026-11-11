<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Publikasi & Informasi';
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?string $modelLabel = 'Pengumuman';
    protected static ?string $pluralModelLabel = 'Daftar Pengumuman';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Pengumuman')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Pengumuman')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai Tayang')
                            ->default(now()),
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Berakhir'),
                        \FilamentTiptapEditor\TiptapEditor::make('isi')
                            ->label('Isi Lengkap Pengumuman (Editor Lengkap Mirip MS Word)')
                            ->profile('default')
                            ->disk('public')
                            ->directory('announcements-content')
                            ->maxContentWidth('5xl')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('file_lampiran')
                            ->label('Lampiran File Dokumen / Surat Edaran (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('announcements')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                        Forms\Components\Toggle::make('tampil_di_beranda')
                            ->label('Sematkan / Tampilkan di Papan Pengumuman Beranda')
                            ->helperText('Aktifkan agar pengumuman ini tampil di kartu agenda Papan Pengumuman Beranda (maksimal 2 agenda).')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Pengumuman')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tampil_di_beranda')
                    ->label('Di Beranda')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('file_lampiran')
                    ->label('Lampiran PDF')
                    ->boolean()
                    ->state(fn ($record) => !empty($record->file_lampiran))
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
