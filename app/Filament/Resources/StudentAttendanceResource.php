<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAttendanceResource\Pages;
use App\Models\Student;
use App\Models\StudentAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentAttendanceResource extends Resource
{
    protected static ?string $model = StudentAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Presensi & Kehadiran Digital';
    protected static ?string $navigationLabel = 'Presensi Harian Siswa';
    protected static ?string $modelLabel = 'Presensi Siswa';
    protected static ?string $pluralModelLabel = 'Presensi Siswa per Rombel';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Catatan Presensi Harian Siswa')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('student_class_id')
                            ->label('Rombongan Belajar / Kelas')
                            ->relationship('studentClass', 'nama')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('student_id', null)),

                        Forms\Components\Select::make('student_id')
                            ->label('Nama Siswa')
                            ->options(function (Get $get) {
                                $classId = $get('student_class_id');
                                if (! $classId) {
                                    return Student::limit(50)->pluck('nama', 'id');
                                }
                                return Student::where('student_class_id', $classId)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'id');
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Presensi')
                            ->options([
                                'Hadir' => 'Hadir (H)',
                                'Sakit (S)' => 'Sakit (S)',
                                'Izin (I)' => 'Izin (I)',
                                'Alpa (A)' => 'Alpa / Tanpa Keterangan (A)',
                            ])
                            ->default('Hadir')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('dicatat_oleh')
                            ->label('Dicatat Oleh (Guru Kelas / Wali)')
                            ->placeholder('Contoh: Bpk. Agi Hamdani')
                            ->default(fn () => auth()->user()?->name ?? 'Guru Kelas'),

                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan / Alasan')
                            ->placeholder('Contoh: Ada surat izin dari orang tua')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('studentClass.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Hadir',
                        'info' => 'Sakit (S)',
                        'warning' => 'Izin (I)',
                        'danger' => 'Alpa (A)',
                    ]),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('dicatat_oleh')
                    ->label('Pencatat')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('student_class_id')
                    ->label('Filter Kelas')
                    ->relationship('studentClass', 'nama'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Sakit (S)' => 'Sakit',
                        'Izin (I)' => 'Izin',
                        'Alpa (A)' => 'Alpa',
                    ]),

                Tables\Filters\Filter::make('hari_ini')
                    ->label('Presensi Hari Ini')
                    ->query(fn ($query) => $query->whereDate('tanggal', today())),
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
            'index' => Pages\ListStudentAttendances::route('/'),
            'create' => Pages\CreateStudentAttendance::route('/create'),
            'edit' => Pages\EditStudentAttendance::route('/{record}/edit'),
        ];
    }
}
