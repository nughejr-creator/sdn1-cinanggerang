<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolProgramResource\Pages;
use App\Models\SchoolProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolProgramResource extends Resource
{
    protected static ?string $model = SchoolProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Publikasi & Informasi';
    protected static ?string $navigationLabel = 'Program Kegiatan Sekolah';
    protected static ?string $modelLabel = 'Program Kegiatan';
    protected static ?string $pluralModelLabel = 'Program Kegiatan Sekolah';
    protected static ?int $navigationSort = 5;

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Program Kegiatan')
                    ->description('Kelola master data kegiatan Ekstrakurikuler, Kokurikuler, dan Pembiasaan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kegiatan')
                            ->placeholder('Contoh: Pramuka / Zero Waste / Sholat Dhuha')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->label('Kategori Utama')
                            ->options([
                                'ekstrakurikuler' => 'Ekstrakurikuler (Bakat, Minat & Seni)',
                                'kokurikuler' => 'Kokurikuler (P5, Lingkungan & Karakter)',
                                'pembiasaan' => 'Pembiasaan (Ibadah, Kebugaran & Literasi)',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat Kegiatan')
                            ->placeholder('Tuliskan ringkasan tujuan, jadwal, atau sasaran kegiatan...')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Kegiatan yang aktif akan tampil di form posting artikel berita guru.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ekstrakurikuler' => 'warning',
                        'kokurikuler' => 'success',
                        'pembiasaan' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'kokurikuler' => 'Kokurikuler',
                        'pembiasaan' => 'Pembiasaan',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Jumlah Warta')
                    ->counts('posts')
                    ->badge()
                    ->color('primary')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->description),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('category')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'kokurikuler' => 'Kokurikuler',
                        'pembiasaan' => 'Pembiasaan',
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchoolPrograms::route('/'),
            'create' => Pages\CreateSchoolProgram::route('/create'),
            'edit' => Pages\EditSchoolProgram::route('/{record}/edit'),
        ];
    }
}
