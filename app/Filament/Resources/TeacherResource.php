<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Data Pokok Sekolah';
    protected static ?string $navigationLabel = 'Guru & Tenaga Kependidikan';
    protected static ?string $modelLabel = 'Guru / Tendik';
    protected static ?string $pluralModelLabel = 'Daftar Guru & Tendik';
    protected static ?int $navigationSort = 1;

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
                Forms\Components\Section::make('Informasi Pribadi & Jabatan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gelar')
                            ->label('Gelar Akademik')
                            ->placeholder('Contoh: S.Pd / M.Pd')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->placeholder('Nomor Induk Pegawai')
                            ->maxLength(30),
                        Forms\Components\TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->maxLength(30),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan / Tugas')
                            ->placeholder('Kepala Sekolah / Guru Kelas VI / Guru PAI')
                            ->required(),
                        Forms\Components\Select::make('status_kepegawaian')
                            ->label('Status Kepegawaian')
                            ->options([
                                'PNS' => 'PNS',
                                'PPPK' => 'PPPK',
                                'PPPK Paruh Waktu' => 'PPPK Paruh Waktu',
                                'Honor Daerah TK.II' => 'Honor Daerah TK.II',
                                'Guru Honorer Sekolah' => 'Guru Honorer Sekolah',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('pendidikan_terakhir')
                            ->label('Pendidikan Terakhir')
                            ->placeholder('S1 / S2 / SMA'),
                        Forms\Components\TextInput::make('jurusan')
                            ->label('Program Studi / Jurusan')
                            ->placeholder('Pendidikan Dasar / Guru Kelas SD'),
                        Forms\Components\TextInput::make('mapel_diajarkan')
                            ->label('Mata Pelajaran yang Diampu')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Kontak & Foto Profil')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_hp')
                            ->label('Nomor WhatsApp / HP')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email(),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Profil Guru')
                            ->image()
                            ->disk('public')
                            ->directory('teachers')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif di Sekolah')
                            ->default(true),
                        Forms\Components\TextInput::make('urutan')
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
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn ($record) => $record->nama . ($record->gelar ? ', ' . $record->gelar : ''))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'PNS' => 'success',
                        'PPPK', 'PPPK Paruh Waktu' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. WhatsApp')
                    ->icon('heroicon-o-phone'),
            ])
            ->defaultSort('urutan')
            ->filters([
                Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->options([
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'PPPK Paruh Waktu' => 'PPPK Paruh Waktu',
                        'Honor Daerah TK.II' => 'Honor Daerah TK.II',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn () => auth()->user()?->role === 'Admin'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
