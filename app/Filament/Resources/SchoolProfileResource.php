<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolProfileResource\Pages;
use App\Models\SchoolProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolProfileResource extends Resource
{
    protected static ?string $model = SchoolProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Pengaturan Sekolah';
    protected static ?string $navigationLabel = 'Profil & Identitas Sekolah';
    protected static ?string $modelLabel = 'Profil Sekolah';
    protected static ?string $pluralModelLabel = 'Profil & Identitas';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Satuan Pendidikan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Sekolah')
                            ->required(),
                        Forms\Components\TextInput::make('npsn')
                            ->label('NPSN')
                            ->required(),
                        Forms\Components\Select::make('akreditasi')
                            ->label('Nilai Akreditasi Sekolah')
                            ->options([
                                'A' => 'A (Unggul)',
                                'B' => 'B (Baik)',
                                'C' => 'C (Cukup)',
                                'Belum Terakreditasi' => 'Belum Terakreditasi',
                            ])
                            ->default('B')
                            ->required()
                            ->helperText('Nilai akreditasi resmi BAN-S/M yang terhubung otomatis ke kop laporan rekap presensi, PPDB, dan profil sekolah.'),
                        Forms\Components\Select::make('pkks_status')
                            ->label('Status Akses Modul E-PKKS Digital')
                            ->options([
                                'active' => '🟢 Aktif (Portal Instrumen Dibuka)',
                                'inactive' => '🔒 Nonaktif (Portal Ditutup / Standby Screen)',
                            ])
                            ->default('active')
                            ->required()
                            ->helperText('Kontrol akses global: jika nonaktif, menu e-PKKS di navbar akan bertanda Tutup dan diarahkan ke layar standby.'),
                        Forms\Components\TextInput::make('kepala_sekolah')
                            ->label('Nama Kepala Sekolah')
                            ->required(),
                        Forms\Components\TextInput::make('nip_kepala_sekolah')
                            ->label('NIP Kepala Sekolah'),
                        Forms\Components\TextInput::make('telepon')
                            ->label('Nomor Telepon'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Resmi'),
                        Forms\Components\TextInput::make('website')
                            ->label('Alamat Website'),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Koordinat GPS & Radius Presensi (Geofencing)')
                    ->description('Tentukan titik koordinat SDN Cinanggerang I dan radius jarak toleransi untuk presensi digital guru')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude Sekolah')
                            ->numeric()
                            ->default(-6.89437395)
                            ->required()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('bukaMap')
                                    ->icon('heroicon-m-map-pin')
                                    ->tooltip('Buka Titik Koordinat di Google Maps')
                                    ->url(fn ($get) => "https://www.google.com/maps?q={$get('latitude')},{$get('longitude')}", shouldOpenInNewTab: true)
                            )
                            ->helperText('Koordinat lintang SDN Cinanggerang I'),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude Sekolah')
                            ->numeric()
                            ->default(107.87197894)
                            ->required()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('bukaMap2')
                                    ->icon('heroicon-m-map-pin')
                                    ->tooltip('Buka Titik Koordinat di Google Maps')
                                    ->url(fn ($get) => "https://www.google.com/maps?q={$get('latitude')},{$get('longitude')}", shouldOpenInNewTab: true)
                            )
                            ->helperText('Koordinat bujur SDN Cinanggerang I'),
                        Forms\Components\TextInput::make('radius_absen_meter')
                            ->label('Radius Presensi (Meter)')
                            ->numeric()
                            ->default(100)
                            ->required()
                            ->suffix('Meter')
                            ->helperText('Guru hanya bisa absen jika berada dalam jarak radius ini (misal: 100 meter).'),
                    ]),

                Forms\Components\Section::make('Aset Branding & Identitas Visual Sekolah')
                    ->description('Unggah logo resmi, favicon browser, foto gedung, dan struktur organisasi')
                    ->columns(3)
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Sekolah (SDN Cinanggerang I)')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('logo_tutwuri')
                            ->label('Logo Tut Wuri Handayani')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('logo_pemda')
                            ->label('Logo Pemda (Kab. Sumedang)')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('favicon')
                            ->label('Favicon (Ikon Tab Browser)')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('foto_kepala_sekolah')
                            ->label('Foto Resmi Kepala Sekolah')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('foto_bangunan')
                            ->label('Foto Gedung / Bangunan Sekolah')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->openable(),
                        Forms\Components\FileUpload::make('banner_hero')
                            ->label('Banner Utama Hero Section (Halaman Beranda Rasio 16:9)')
                            ->helperText('Foto kegiatan atau gedung sekolah resolusi tinggi. Foto ini langsung tampil di bingkai sisi kanan Hero Section Beranda.')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->columnSpanFull()
                            ->openable(),
                        Forms\Components\FileUpload::make('bagan_struktur_organisasi')
                            ->label('Gambar Bagan Struktur Organisasi Sekolah')
                            ->image()
                            ->disk('public')
                            ->directory('school-branding')
                            ->columnSpanFull()
                            ->openable(),
                    ]),

                Forms\Components\Section::make('Pengaturan Tampilan Beranda & Bilah Pengumuman (Ticker)')
                    ->description('Kelola teks pengumuman berjalan (ticker bar) di bawah navbar halaman depan')
                    ->schema([
                        Forms\Components\Toggle::make('is_ticker_active')
                            ->label('Aktifkan Bilah Pengumuman Berjalan (Ticker Bar)')
                            ->helperText('Jika aktif, bilah pengumuman akan tampil di bawah menu navigasi.')
                            ->default(true),

                        Forms\Components\Textarea::make('ticker_text')
                            ->label('Isi Teks Pengumuman Berjalan')
                            ->placeholder('Pelaksanaan Sumatif Akhir Semester (SAS) 1 T.A. 2026/2027 SD Negeri Cinanggerang I dijadwalkan mulai 30 November s.d. 12 Desember 2026...')
                            ->helperText('Teks berjalan yang tampil di bilah pengumuman di bawah navbar.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Visi, Misi & Sambutan Kepala Sekolah')
                    ->description('Editor teks terformat ala MS Word dilengkapi perataan teks (Kiri, Tengah, Kanan, Justify), heading, list, penekanan font, dan riwayat revisi.')
                    ->schema([
                        \FilamentTiptapEditor\TiptapEditor::make('visi')
                            ->label('Visi Sekolah')
                            ->helperText('Rumusan visi resmi satuan pendidikan SDN Cinanggerang I.')
                            ->profile('default')
                            ->columnSpanFull(),
                        \FilamentTiptapEditor\TiptapEditor::make('misi')
                            ->label('Misi Sekolah')
                            ->helperText('Poin-poin misi sekolah (dapat menggunakan bullet list atau numbered list seperti di MS Word).')
                            ->profile('default')
                            ->columnSpanFull(),
                        \FilamentTiptapEditor\TiptapEditor::make('sambutan_kepala_sekolah')
                            ->label('Sambutan Kepala Sekolah (Aep Yayat, S.Pd)')
                            ->helperText('Amanat resmi kepala sekolah untuk beranda portal sekolah (Editor Lengkap Mirip MS Word).')
                            ->profile('default')
                            ->disk('public')
                            ->directory('headmaster-speech')
                            ->maxContentWidth('5xl')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo Sekolah'),
                Tables\Columns\ImageColumn::make('logo_pemda')
                    ->label('Logo Pemda'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sekolah'),
                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->badge(),
                Tables\Columns\TextColumn::make('akreditasi')
                    ->label('Akreditasi')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('pkks_status')
                    ->label('Status E-PKKS')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Dibuka' : 'Ditutup')
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('kepala_sekolah')
                    ->label('Kepala Sekolah'),
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchoolProfiles::route('/'),
            'create' => Pages\CreateSchoolProfile::route('/create'),
            'edit' => Pages\EditSchoolProfile::route('/{record}/edit'),
        ];
    }
}
