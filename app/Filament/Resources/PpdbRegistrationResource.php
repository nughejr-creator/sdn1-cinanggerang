<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbRegistrationResource\Pages;
use App\Models\PpdbRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PpdbRegistrationResource extends Resource
{
    protected static ?string $model = PpdbRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Layanan PPDB Online';
    protected static ?string $navigationLabel = 'Pendaftar Siswa Baru (PPDB)';
    protected static ?string $modelLabel = 'Pendaftar PPDB';
    protected static ?string $pluralModelLabel = 'Data Pendaftar PPDB';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Calon Peserta Didik')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Registrasi')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),

                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('nik')
                            ->label('NIK (16 Digit)')
                            ->length(16)
                            ->required(),

                        Forms\Components\TextInput::make('nisn')
                            ->label('NISN (Jika Ada)')
                            ->maxLength(10),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),

                        Forms\Components\Select::make('asal_jenjang')
                            ->label('Asal Jenjang Pendidikan')
                            ->options([
                                'TK' => 'TK (Taman Kanak-kanak)',
                                'RA' => 'RA (Raudhatul Athfal)',
                                'PAUD' => 'PAUD',
                                'Rumah Tangga' => 'Langsung dari Rumah Tangga',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('nama_asal_sekolah')
                            ->label('Nama Asal Sekolah')
                            ->placeholder('Contoh: TK PGRI Pamulihan'),
                    ]),

                Forms\Components\Section::make('Data Orang Tua & Domisili')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ibu_kandung')
                            ->label('Nama Ibu Kandung')
                            ->required(),

                        Forms\Components\TextInput::make('nama_ayah_kandung')
                            ->label('Nama Ayah Kandung'),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp Aktif')
                            ->tel()
                            ->required(),

                        Forms\Components\Textarea::make('alamat_tinggal')
                            ->label('Alamat Lengkap Tinggal')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Klasifikasi Jalur Pendaftaran & Dokumen Khusus')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('jalur_pendaftaran')
                            ->label('Jalur Pendaftaran')
                            ->options([
                                'Zonasi' => 'Jalur Zonasi',
                                'Afirmasi' => 'Jalur Afirmasi',
                                'Perpindahan Orang Tua' => 'Jalur Perpindahan Tugas Orang Tua',
                            ])
                            ->default('Zonasi')
                            ->required(),

                        Forms\Components\TextInput::make('jarak_ke_sekolah_meter')
                            ->label('Estimasi Jarak ke Sekolah (Meter)')
                            ->numeric()
                            ->suffix('meter'),

                        Forms\Components\TextInput::make('nomor_kip_pkh')
                            ->label('Nomor Kartu KIP / PKH / KKS (Afirmasi)'),

                        Forms\Components\TextInput::make('instansi_tujuan_mutasi')
                            ->label('Instansi Tujuan Penugasan (Perpindahan)')
                            ->columnSpan(2),

                        Forms\Components\FileUpload::make('file_bukti_afirmasi')
                            ->label('Berkas Bukti Afirmasi (KIP/PKH/SKTM)')
                            ->disk('public')
                            ->directory('ppdb_docs')
                            ->openable()
                            ->downloadable(),

                        Forms\Components\FileUpload::make('file_surat_pindah_tugas')
                            ->label('Berkas SK Pindah Tugas Orang Tua')
                            ->disk('public')
                            ->directory('ppdb_docs')
                            ->openable()
                            ->downloadable(),
                    ]),

                Forms\Components\Section::make('Berkas Dokumen Persyaratan')
                    ->columns(3)
                    ->schema([
                        Forms\Components\FileUpload::make('file_kk')
                            ->label('Berkas Kartu Keluarga (KK)')
                            ->disk('public')
                            ->directory('ppdb_docs')
                            ->openable()
                            ->downloadable()
                            ->required(),

                        Forms\Components\FileUpload::make('file_akta')
                            ->label('Berkas Akta Kelahiran')
                            ->disk('public')
                            ->directory('ppdb_docs')
                            ->openable()
                            ->downloadable()
                            ->required(),

                        Forms\Components\FileUpload::make('file_ijazah')
                            ->label('Berkas Ijazah / SKL (TK/RA/PAUD)')
                            ->disk('public')
                            ->directory('ppdb_docs')
                            ->openable()
                            ->downloadable(),
                    ]),

                Forms\Components\Section::make('Keputusan Seleksi & Catatan Panitia')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status_pendaftaran')
                            ->label('Status Hasil Seleksi')
                            ->options([
                                'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                                'Diterima' => 'Diterima di SDN Cinanggerang I',
                                'Ditolak' => 'Ditolak / Berkas Tidak Lengkap',
                            ])
                            ->default('Menunggu Verifikasi')
                            ->required(),

                        Forms\Components\Textarea::make('catatan_panitia')
                            ->label('Catatan Panitia untuk Pendaftar')
                            ->placeholder('Contoh: Berkas telah lengkap. Silakan hadir daftar ulang pada tanggal 10 Juli 2026.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('No. Registrasi')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Calon Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('jalur_pendaftaran')
                    ->label('Jalur')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Zonasi' => 'success',
                        'Afirmasi' => 'warning',
                        'Perpindahan Orang Tua' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn ($state) => $state === 'L' ? 'info' : 'warning'),

                Tables\Columns\TextColumn::make('asal_jenjang')
                    ->label('Asal Jenjang')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->url(fn ($record) => "https://wa.me/" . preg_replace('/^0/', '62', preg_replace('/[^\d]/', '', $record->nomor_whatsapp)), true),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'warning',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'Diterima' => 'heroicon-m-check-badge',
                        'Ditolak' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-clock',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('jalur_pendaftaran')
                    ->label('Filter Jalur PPDB')
                    ->options([
                        'Zonasi' => 'Jalur Zonasi',
                        'Afirmasi' => 'Jalur Afirmasi',
                        'Perpindahan Orang Tua' => 'Jalur Perpindahan Orang Tua',
                    ]),
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('asal_jenjang')
                    ->label('Filter Asal Jenjang')
                    ->options([
                        'TK' => 'TK',
                        'RA' => 'RA',
                        'PAUD' => 'PAUD',
                        'Rumah Tangga' => 'Rumah Tangga',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('cetakKelulusan')
                    ->label('Cetak SK')
                    ->icon('heroicon-m-printer')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_pendaftaran === 'Diterima')
                    ->url(fn ($record) => route('ppdb.print-kelulusan', $record->registration_number), shouldOpenInNewTab: true),

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
            'index' => Pages\ListPpdbRegistrations::route('/'),
            'edit' => Pages\EditPpdbRegistration::route('/{record}/edit'),
        ];
    }
}
