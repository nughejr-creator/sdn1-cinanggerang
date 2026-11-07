<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentClassResource\Pages;
use App\Models\StudentClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentClassResource extends Resource
{
    protected static ?string $model = StudentClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Data Pokok Sekolah';
    protected static ?string $navigationLabel = 'Rombongan Belajar (Rombel)';
    protected static ?string $modelLabel = 'Rombel';
    protected static ?string $pluralModelLabel = 'Rombongan Belajar';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->role === 'Admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Rombel')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Rombel')
                            ->placeholder('Kelas I / Kelas IIA / Kelas VI')
                            ->required(),
                        Forms\Components\TextInput::make('tingkat')
                            ->label('Tingkat Kelas (1-6)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('wali_kelas')
                            ->label('Wali Kelas')
                            ->required(),
                        Forms\Components\Select::make('teacher_id')
                            ->label('Akun Guru Wali Kelas')
                            ->relationship('teacher', 'nama'),
                        Forms\Components\TextInput::make('ruangan')
                            ->label('Nama Ruangan')
                            ->placeholder('Ruang Kelas I'),
                        Forms\Components\TextInput::make('kurikulum')
                            ->label('Kurikulum')
                            ->default('Kurikulum SD Merdeka'),
                        Forms\Components\TextInput::make('tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->default('2026/2027'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Rombel')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('wali_kelas')
                    ->label('Wali Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('Total Siswa')
                    ->counts('students')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('kurikulum')
                    ->label('Kurikulum'),
            ])
            ->defaultSort('tingkat')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentClasses::route('/'),
            'create' => Pages\CreateStudentClass::route('/create'),
            'edit' => Pages\EditStudentClass::route('/{record}/edit'),
        ];
    }
}
