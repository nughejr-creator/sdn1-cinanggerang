<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolHolidayResource\Pages;
use App\Models\SchoolHoliday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolHolidayResource extends Resource
{
    protected static ?string $model = SchoolHoliday::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Presensi & Kehadiran Digital';
    protected static ?string $navigationLabel = 'Pengaturan Hari Libur';
    protected static ?string $modelLabel = 'Hari Libur';
    protected static ?string $pluralModelLabel = 'Daftar Hari Libur Sekolah';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Hari Libur Sekolah')
                    ->description('Tentukan hari libur nasional, cuti bersama, atau libur khusus sekolah di luar hari Minggu.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama / Keterangan Hari Libur')
                            ->placeholder('Contoh: Hari Raya Idul Fitri 1447 H / Libur Semester Ganjil')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Kategori Libur')
                            ->options([
                                'Hari Libur Nasional' => 'Hari Libur Nasional',
                                'Cuti Bersama' => 'Cuti Bersama',
                                'Libur Semester / Kenaikan Kelas' => 'Libur Semester / Kenaikan Kelas',
                                'Libur Khusus Sekolah' => 'Libur Khusus Sekolah (Acara Internal)',
                                'Hari Libur Keagamaan' => 'Hari Libur Keagamaan',
                            ])
                            ->default('Hari Libur Nasional')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Jika aktif, hari ini akan otomatis mengecualikan guru dari kewajiban presensi.')
                            ->default(true),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->helperText('Sama dengan tanggal mulai jika hanya libur 1 hari.')
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('description')
                            ->label('Catatan / Dasar Penetapan (SK / Edaran Dinas)')
                            ->placeholder('Contoh: Berdasarkan SKB 3 Menteri No. ...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Hari Libur')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'danger' => 'Hari Libur Nasional',
                        'warning' => 'Cuti Bersama',
                        'info' => 'Libur Semester / Kenaikan Kelas',
                        'primary' => fn ($state) => in_array($state, ['Libur Khusus Sekolah', 'Hari Libur Keagamaan']),
                    ]),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('start_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter Kategori')
                    ->options([
                        'Hari Libur Nasional' => 'Hari Libur Nasional',
                        'Cuti Bersama' => 'Cuti Bersama',
                        'Libur Semester / Kenaikan Kelas' => 'Libur Semester / Kenaikan Kelas',
                        'Libur Khusus Sekolah' => 'Libur Khusus Sekolah',
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
            'index' => Pages\ListSchoolHolidays::route('/'),
            'create' => Pages\CreateSchoolHoliday::route('/create'),
            'edit' => Pages\EditSchoolHoliday::route('/{record}/edit'),
        ];
    }
}
