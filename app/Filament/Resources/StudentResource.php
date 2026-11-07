<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\StudentClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Pokok Sekolah';
    protected static ?string $navigationLabel = 'Data Siswa (Peserta Didik)';
    protected static ?string $modelLabel = 'Peserta Didik';
    protected static ?string $pluralModelLabel = 'Daftar Siswa';
    protected static ?int $navigationSort = 3;

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
                Forms\Components\Section::make('Identitas Siswa')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap Siswa')
                            ->required(),
                        Forms\Components\Select::make('student_class_id')
                            ->label('Rombel / Kelas')
                            ->relationship('studentClass', 'nama')
                            ->required(),
                        Forms\Components\TextInput::make('nisn')
                            ->label('NISN')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nipd')
                            ->label('NIPD')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK Siswa')
                            ->maxLength(25),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir'),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir'),
                        Forms\Components\Select::make('status')
                            ->label('Status Siswa')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Lulus' => 'Lulus',
                                'Mutasi Keluar' => 'Mutasi Keluar',
                            ])
                            ->default('Aktif')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Data Orang Tua & Bantuan Pendidikan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah'),
                        Forms\Components\TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah'),
                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu'),
                        Forms\Components\TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu'),
                        Forms\Components\TextInput::make('no_hp')
                            ->label('Nomor Telepon / HP'),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Rumah')
                            ->columnSpanFull(),
                        Forms\Components\Checkbox::make('layak_pip')
                            ->label('Penerima / Layak PIP (Program Indonesia Pintar)'),
                        Forms\Components\Checkbox::make('penerima_kip')
                            ->label('Pemegang KIP (Kartu Indonesia Pintar)'),
                        Forms\Components\Checkbox::make('penerima_pkh')
                            ->label('Penerima PKH / KPS / KKS'),
                        Forms\Components\TextInput::make('alasan_layak_pip')
                            ->label('Keterangan Kelayakan PIP')
                            ->placeholder('Contoh: Siswa Miskin/Rentan Miskin / Pemegang PKH')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('studentClass.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge(),
                Tables\Columns\IconColumn::make('layak_pip')
                    ->label('PIP')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success'),
                Tables\Columns\TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Aktif' ? 'success' : 'gray'),
            ])
            ->defaultSort('nama')
            ->filters([
                Tables\Filters\SelectFilter::make('student_class_id')
                    ->label('Filter Kelas')
                    ->relationship('studentClass', 'nama'),
                Tables\Filters\TernaryFilter::make('layak_pip')
                    ->label('Siswa Penerima PIP'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
