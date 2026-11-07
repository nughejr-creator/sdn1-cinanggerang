<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbApplicantResource\Pages;
use App\Models\PpdbApplicant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PpdbApplicantResource extends Resource
{
    protected static ?string $model = PpdbApplicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Layanan PPDB Online';
    protected static ?string $navigationLabel = 'Pendaftar Siswa Baru';
    protected static ?string $modelLabel = 'Calon Siswa Baru';
    protected static ?string $pluralModelLabel = 'Daftar Calon Siswa PPDB';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Calon Siswa')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nomor_pendaftaran')
                            ->label('Nomor Registrasi PPDB')
                            ->default(fn () => 'PPDB-' . date('Y') . '-' . rand(1000, 9999))
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->default('2026/2027')
                            ->required(),
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap Anak')
                            ->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK Calon Siswa')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nisn')
                            ->label('NISN (Jika dari TK/PAUD)')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        Forms\Components\TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah (TK / PAUD / RA)')
                            ->placeholder('Contoh: TK Satu Atap Cinanggerang 1 / TK Mutiara'),
                        Forms\Components\Select::make('jalur_pendaftaran')
                            ->label('Jalur Pendaftaran')
                            ->options([
                                'Zonasi' => 'Zonasi (Jarak Rumah Dekat)',
                                'Afirmasi / KIP' => 'Afirmasi (Keluarga Ekonomi Kurang Mampu / KIP / PKH)',
                                'Perpindahan Orang Tua' => 'Perpindahan Tugas Orang Tua / Wali',
                            ])
                            ->default('Zonasi')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Data Orang Tua & Domisili')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah Kandung'),
                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu Kandung'),
                        Forms\Components\TextInput::make('pekerjaan_ortu')
                            ->label('Pekerjaan Orang Tua'),
                        Forms\Components\TextInput::make('no_hp_whatsapp')
                            ->label('Nomor WhatsApp Aktif (Untuk Info Seleksi)')
                            ->required(),
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Rumah Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('dusun')
                            ->label('Dusun / Blok')
                            ->placeholder('Contoh: Ciidras / Panenjoan / Kubang'),
                        Forms\Components\TextInput::make('rt')
                            ->label('RT'),
                        Forms\Components\TextInput::make('rw')
                            ->label('RW'),
                    ]),

                Forms\Components\Section::make('Berkas Persyaratan Digital')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('berkas_kartu_keluarga')
                            ->label('Kartu Keluarga (KK)')
                            ->directory('ppdb-berkas')
                            ->openable(),
                        Forms\Components\FileUpload::make('berkas_akta_kelahiran')
                            ->label('Akta Kelahiran')
                            ->directory('ppdb-berkas')
                            ->openable(),
                        Forms\Components\FileUpload::make('berkas_ijazah_tk')
                            ->label('Surat Keterangan / Ijazah TK (Jika Ada)')
                            ->directory('ppdb-berkas')
                            ->openable(),
                        Forms\Components\FileUpload::make('berkas_kip_pkh')
                            ->label('Kartu KIP / PKH / KPS (Khusus Jalur Afirmasi)')
                            ->directory('ppdb-berkas')
                            ->openable(),
                    ]),

                Forms\Components\Section::make('Verifikasi Panitia PPDB')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status_seleksi')
                            ->label('Status Seleksi')
                            ->options([
                                'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                                'Berkas Valid' => 'Berkas Valid (Lengkap)',
                                'Perlu Perbaikan Berkas' => 'Perlu Perbaikan Berkas',
                                'Diterima' => 'Diterima (Lolos Seleksi)',
                                'Tidak Diterima' => 'Tidak Diterima',
                            ])
                            ->default('Menunggu Verifikasi')
                            ->required(),
                        Forms\Components\Textarea::make('catatan_panitia')
                            ->label('Catatan Panitia untuk Orang Tua')
                            ->placeholder('Contoh: Berkas Akta belum jelas, mohon upload ulang.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pendaftaran')
                    ->label('No. Daftar')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Calon Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge(),
                Tables\Columns\TextColumn::make('jalur_pendaftaran')
                    ->label('Jalur')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('no_hp_whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text'),
                Tables\Columns\TextColumn::make('status_seleksi')
                    ->label('Status Seleksi')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Diterima' => 'success',
                        'Berkas Valid' => 'info',
                        'Perlu Perbaikan Berkas' => 'warning',
                        'Tidak Diterima' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_seleksi')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Berkas Valid' => 'Berkas Valid',
                        'Perlu Perbaikan Berkas' => 'Perlu Perbaikan Berkas',
                        'Diterima' => 'Diterima',
                        'Tidak Diterima' => 'Tidak Diterima',
                    ]),
                Tables\Filters\SelectFilter::make('jalur_pendaftaran')
                    ->options([
                        'Zonasi' => 'Zonasi',
                        'Afirmasi / KIP' => 'Afirmasi / KIP',
                        'Perpindahan Orang Tua' => 'Perpindahan Orang Tua',
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
            'index' => Pages\ListPpdbApplicants::route('/'),
            'create' => Pages\CreatePpdbApplicant::route('/create'),
            'edit' => Pages\EditPpdbApplicant::route('/{record}/edit'),
        ];
    }
}
