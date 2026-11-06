<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherAttendanceResource\Pages;
use App\Models\TeacherAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherAttendanceResource extends Resource
{
    protected static ?string $model = TeacherAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = 'Presensi & Kehadiran Digital';
    protected static ?string $navigationLabel = 'Presensi Guru & Tendik';
    protected static ?string $modelLabel = 'Presensi Guru';
    protected static ?string $pluralModelLabel = 'Presensi Guru & Tendik';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Presensi Guru & Tenaga Kependidikan')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->label('Nama Guru / Tendik')
                            ->relationship('teacher', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Presensi')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Kehadiran')
                            ->options([
                                'Hadir Tepat Waktu' => 'Hadir Tepat Waktu',
                                'Hadir Terlambat' => 'Hadir Terlambat',
                                'Sakit (SKD)' => 'Sakit (Surat Dokter)',
                                'Izin Resmi' => 'Izin Resmi',
                                'Dinas Luar' => 'Dinas Luar',
                                'Dinas Luar / Pelatihan' => 'Dinas Luar / Pelatihan',
                                'Cuti' => 'Cuti',
                                'Tanpa Keterangan (Alpa)' => 'Tanpa Keterangan (Alpa)',
                            ])
                            ->default('Hadir Tepat Waktu')
                            ->required()
                            ->native(false),

                        Forms\Components\TimePicker::make('jam_masuk')
                            ->label('Jam Masuk')
                            ->default(now()->format('H:i')),

                        Forms\Components\TimePicker::make('jam_pulang')
                            ->label('Jam Pulang'),

                        Forms\Components\Select::make('metode')
                            ->label('Metode Presensi')
                            ->options([
                                'Presensi Mandiri Web' => 'Presensi Mandiri Web',
                                'Input Manual Admin' => 'Input Manual Admin',
                                'Fingerprint / Mesin' => 'Fingerprint / Mesin',
                            ])
                            ->default('Input Manual Admin')
                            ->required(),

                        Forms\Components\FileUpload::make('foto_bukti')
                            ->label('Foto Bukti (Selfie / Surat Izin / Surat Tugas)')
                            ->image()
                            ->disk('public')
                            ->directory('attendances')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->placeholder('Contoh: Mengikuti Rapat KKG di Gugus Pamulihan')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Informasi & Bukti Dinas Luar (DL)')
                    ->description('Rincian penugasan dinas luar dan berkas bukti surat tugas atau dokumentasi kegiatan')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('attendance_type')
                            ->label('Tipe Presensi')
                            ->options([
                                'Reguler' => 'Reguler (Di Lingkungan Sekolah)',
                                'Dinas Luar' => 'Dinas Luar (Bypass Geofencing)',
                            ])
                            ->default('Reguler'),

                        Forms\Components\Select::make('duty_proof_type')
                            ->label('Jenis Bukti Validasi')
                            ->options([
                                'Surat Tugas / Undangan' => 'Surat Tugas / Undangan',
                                'Foto Kegiatan di Lokasi' => 'Foto Kegiatan di Lokasi',
                            ]),

                        Forms\Components\TextInput::make('duty_location_name')
                            ->label('Nama Tempat / Instansi Dinas Luar')
                            ->placeholder('Contoh: Disdik Kab. Sumedang / Gedung KKG Pamulihan'),

                        Forms\Components\FileUpload::make('duty_proof_file')
                            ->label('Berkas Surat Tugas / Foto Kegiatan')
                            ->disk('public')
                            ->directory('presensi_dinas_luar')
                            ->openable()
                            ->downloadable(),

                        Forms\Components\Textarea::make('duty_description')
                            ->label('Keterangan Agenda Kegiatan')
                            ->placeholder('Contoh: Mengikuti Rapat Koordinasi Kurikulum Merdeka')
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

                Tables\Columns\TextColumn::make('teacher.nama')
                    ->label('Nama Guru / Tendik')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('attendance_type')
                    ->label('Mode')
                    ->badge()
                    ->color(fn ($state) => $state === 'Dinas Luar' ? 'info' : 'gray')
                    ->icon(fn ($state) => $state === 'Dinas Luar' ? 'heroicon-m-briefcase' : 'heroicon-m-map-pin'),

                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Absen Datang')
                    ->time('H:i')
                    ->description(fn ($record) => $record?->jarak_meter !== null ? "GPS: {$record->jarak_meter} m" : null)
                    ->color('success')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jam_pulang')
                    ->label('Absen Pulang')
                    ->time('H:i')
                    ->description(fn ($record) => $record?->jarak_pulang_meter !== null ? "GPS: {$record->jarak_pulang_meter} m" : null)
                    ->color('info')
                    ->placeholder('Belum Pulang'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Hadir Tepat Waktu',
                        'warning' => fn ($state) => in_array($state, ['Hadir Terlambat', 'Izin Resmi']),
                        'info' => fn ($state) => in_array($state, ['Dinas Luar', 'Dinas Luar / Pelatihan', 'Sakit (SKD)']),
                        'danger' => 'Tanpa Keterangan (Alpa)',
                    ]),

                Tables\Columns\TextColumn::make('jarak_meter')
                    ->label('Jarak GPS')
                    ->formatStateUsing(fn ($state) => $state !== null ? "{$state} meter" : '-')
                    ->badge()
                    ->color(fn ($record) => $record?->attendance_type === 'Dinas Luar' ? 'info' : (($record?->is_in_radius ?? true) ? 'success' : 'danger'))
                    ->description(fn ($record) => $record?->attendance_type === 'Dinas Luar' ? 'Dinas Luar (Bypass)' : (($record?->is_in_radius ?? true) ? 'Dalam Zona' : 'Luar Zona')),

                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('foto_bukti')
                    ->label('Selfie')
                    ->circular()
                    ->disk('public'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(25)
                    ->placeholder('-'),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('attendance_type')
                    ->label('Mode Presensi')
                    ->options([
                        'Reguler' => 'Reguler (Di Sekolah)',
                        'Dinas Luar' => 'Dinas Luar (DL)',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Hadir Tepat Waktu' => 'Hadir Tepat Waktu',
                        'Hadir Terlambat' => 'Hadir Terlambat',
                        'Dinas Luar' => 'Dinas Luar (DL)',
                        'Sakit (SKD)' => 'Sakit (SKD)',
                        'Izin Resmi' => 'Izin Resmi',
                        'Dinas Luar / Pelatihan' => 'Dinas Luar / Pelatihan',
                        'Tanpa Keterangan (Alpa)' => 'Alpa',
                    ]),
                Tables\Filters\Filter::make('hari_ini')
                    ->label('Presensi Hari Ini')
                    ->query(fn ($query) => $query->whereDate('tanggal', today())),
            ])
            ->actions([
                Tables\Actions\Action::make('lihatBukti')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('info')
                    ->visible(fn ($record) => $record->attendance_type === 'Dinas Luar' || !empty($record->duty_proof_file))
                    ->modalHeading(fn ($record) => "Bukti Tugas: " . ($record->teacher?->nama ?? 'GTK'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.modals.attendance-proof', ['record' => $record])),
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
            'index' => Pages\ListTeacherAttendances::route('/'),
            'create' => Pages\CreateTeacherAttendance::route('/create'),
            'edit' => Pages\EditTeacherAttendance::route('/{record}/edit'),
        ];
    }
}
