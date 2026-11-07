<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PkksEvidenceResource\Pages;
use App\Models\PkksEvidence;
use App\Models\PkksIndicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PkksEvidenceResource extends Resource
{
    protected static ?string $model = PkksEvidence::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'E-PKKS (Dokumen Mutu)';
    protected static ?string $navigationLabel = 'Eviden & Bukti Fisik PKKS';
    protected static ?string $modelLabel = 'Bukti Fisik PKKS';
    protected static ?string $pluralModelLabel = 'Arsip Bukti Fisik PKKS';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pemetaan Indikator & Berkas Eviden')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pkks_indicator_id')
                            ->label('Indikator Mutu (Permendikdasmen No. 23/2026)')
                            ->options(
                                PkksIndicator::orderBy('indicator_number')->get()->mapWithKeys(function ($item) {
                                    return [$item->id => "Indikator {$item->indicator_number} [{$item->competence}]: {$item->title}"];
                                })
                            )
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->label('Nama / Judul Dokumen Bukti Fisik')
                            ->placeholder('Contoh: SK Tim TPPK & Laporan Kegiatan 2025/2026')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Ringkasan / Keterangan Dokumen')
                            ->placeholder('Jelaskan isi berkas bukti fisik ini secara ringkas...')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('file_type')
                            ->label('Tipe Media')
                            ->options([
                                'pdf' => '📄 Dokumen PDF',
                                'video' => '🎥 Video Dokumentasi',
                                'image' => '🖼️ Galeri Foto',
                                'link' => '🔗 Portal / Tautan Web',
                            ])
                            ->default('pdf')
                            ->required(),

                        Forms\Components\Toggle::make('is_public')
                            ->label('Akses Publik')
                            ->helperText('Jika nonaktif, dokumen akan terkunci dan membutuhkan PIN Asesor untuk membukanya.')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Tautan Berkas Digital & Cloud Storage')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('external_url')
                            ->label('Tautan Eksternal (Google Drive / YouTube / TikTok)')
                            ->placeholder('https://drive.google.com/... atau https://youtube.com/...')
                            ->url()
                            ->helperText('Gunakan tautan Google Drive SDN Cinanggerang I atau video dokumentasi resmi.')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Unggah Berkas Lokal (Opsional)')
                            ->directory('pkks-evidences')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->openable()
                            ->downloadable(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indicator.indicator_number')
                    ->label('No. Indikator')
                    ->formatStateUsing(fn ($state) => "Indikator {$state}")
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Bukti Fisik')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('file_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pdf' => 'danger',
                        'video' => 'warning',
                        'image' => 'success',
                        default => 'info',
                    })
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Tables\Columns\IconColumn::make('is_public')
                    ->label('Akses Publik')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-open')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('external_url')
                    ->label('Tautan Drive/Video')
                    ->formatStateUsing(fn ($state) => $state ? 'Buka Tautan' : '-')
                    ->url(fn ($record) => $record->external_url, true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('pkks_indicator_id')
                    ->label('Filter Indikator')
                    ->options(
                        PkksIndicator::orderBy('indicator_number')->pluck('title', 'id')
                    ),
                Tables\Filters\SelectFilter::make('file_type')
                    ->label('Filter Tipe')
                    ->options([
                        'pdf' => 'PDF',
                        'video' => 'Video',
                        'image' => 'Foto',
                        'link' => 'Portal',
                    ]),
            ])
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
            'index' => Pages\ListPkksEvidences::route('/'),
            'create' => Pages\CreatePkksEvidence::route('/create'),
            'edit' => Pages\EditPkksEvidence::route('/{record}/edit'),
        ];
    }
}
