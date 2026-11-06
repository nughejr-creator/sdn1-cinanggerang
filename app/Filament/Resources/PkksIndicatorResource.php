<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PkksIndicatorResource\Pages;
use App\Models\PkksIndicator;
use App\Models\PkksPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PkksIndicatorResource extends Resource
{
    protected static ?string $model = PkksIndicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'E-PKKS (Dokumen Mutu)';
    protected static ?string $navigationLabel = '12 Indikator Permendikdasmen';
    protected static ?string $modelLabel = 'Indikator Mutu PKKS';
    protected static ?string $pluralModelLabel = 'Daftar 12 Indikator PKKS';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Standar & Indikator (Permendikdasmen No. 23 Tahun 2026)')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pkks_period_id')
                            ->label('Periode Tahun Ajaran')
                            ->options(PkksPeriod::pluck('academic_year', 'id'))
                            ->default(fn () => PkksPeriod::first()?->id ?? 1)
                            ->required(),

                        Forms\Components\TextInput::make('indicator_number')
                            ->label('Nomor Indikator (1 s/d 12)')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('competence')
                            ->label('Pilar Kompetensi')
                            ->options([
                                'Kepribadian' => 'Kompetensi Kepribadian (Indikator 1-3)',
                                'Sosial' => 'Kompetensi Sosial (Indikator 4-6)',
                                'Profesional' => 'Kompetensi Profesional (Indikator 7-12)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Keterpenuhan')
                            ->options([
                                'Ada' => 'Ada (Terpenuhi 100%)',
                                'Tidak Ada' => 'Tidak Ada (Belum Terpenuhi)',
                            ])
                            ->default('Ada')
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Indikator Mutu')
                            ->placeholder('Contoh: Integritas dan Perilaku Etis')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Kriteria Standar')
                            ->placeholder('Uraian standar kompetensi kerja kepala sekolah...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Textarea::make('best_practice_notes')
                            ->label('Narasi Praktik Baik SDN Cinanggerang I')
                            ->placeholder('Ceritakan implementasi nyata praktik baik di sekolah...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indicator_number')
                    ->label('No.')
                    ->formatStateUsing(fn ($state) => "Indikator {$state}")
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('competence')
                    ->label('Pilar Kompetensi')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Kepribadian' => 'success',
                        'Sosial' => 'info',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Indikator')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Ada' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('evidences_count')
                    ->counts('evidences')
                    ->label('Jumlah Dokumen')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('indicator_number', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPkksIndicators::route('/'),
            'create' => Pages\CreatePkksIndicator::route('/create'),
            'edit' => Pages\EditPkksIndicator::route('/{record}/edit'),
        ];
    }
}
