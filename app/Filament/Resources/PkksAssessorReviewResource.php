<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PkksAssessorReviewResource\Pages;
use App\Models\PkksAssessorReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PkksAssessorReviewResource extends Resource
{
    protected static ?string $model = PkksAssessorReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'E-PKKS (Dokumen Mutu)';
    protected static ?string $navigationLabel = 'Catatan Asesor Penilai';
    protected static ?string $modelLabel = 'Catatan Asesor Penilai';
    protected static ?string $pluralModelLabel = 'Catatan & Rekomendasi Asesor';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Asesor & Rekomendasi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('assessor_name')
                            ->label('Nama Asesor Penilai')
                            ->required(),
                        Forms\Components\TextInput::make('assessor_agency')
                            ->label('Instansi / Satuan Pengawas')
                            ->required(),
                        Forms\Components\TextInput::make('recommendation_status')
                            ->label('Status Rekomendasi')
                            ->required(),
                        Forms\Components\Select::make('pkks_period_id')
                            ->label('Periode PKKS')
                            ->relationship('period', 'academic_year'),
                    ]),

                Forms\Components\Section::make('Ulasan & Rekomendasi')
                    ->schema([
                        Forms\Components\Textarea::make('performance_review')
                            ->label('Ulasan Kinerja & Praktik Baik')
                            ->rows(4),
                        Forms\Components\Textarea::make('follow_up_recommendation')
                            ->label('Rekomendasi Tindak Lanjut Perbaikan')
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Asesmen')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessor_name')
                    ->label('Nama Asesor')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('assessor_agency')
                    ->label('Instansi / Satuan Pengawas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recommendation_status')
                    ->label('Rekomendasi')
                    ->badge()
                    ->color(fn ($state) => str_contains((string)$state, 'Sangat Baik') ? 'success' : (str_contains((string)$state, 'Cukup') ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('period.academic_year')
                    ->label('Tahun Periode')
                    ->badge(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPkksAssessorReviews::route('/'),
        ];
    }
}
