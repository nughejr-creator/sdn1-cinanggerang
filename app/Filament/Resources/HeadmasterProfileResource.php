<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeadmasterProfileResource\Pages;
use App\Models\HeadmasterProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeadmasterProfileResource extends Resource
{
    protected static ?string $model = HeadmasterProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'E-PKKS (Dokumen Mutu)';
    protected static ?string $navigationLabel = 'Profil & CV Kepala Sekolah';
    protected static ?string $modelLabel = 'Profil Kepala Sekolah';
    protected static ?string $pluralModelLabel = 'Profil & Portofolio Pimpinan';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Resmi Kepala Sekolah')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap & Gelar')
                            ->default('Bpk. Aep Yayat, S.Pd')
                            ->required(),

                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->default('197206102014111001')
                            ->required(),

                        Forms\Components\TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->default('7442750652200022'),

                        Forms\Components\TextInput::make('rank_group')
                            ->label('Pangkat / Golongan Ruang')
                            ->default('Penata Tk. I / IV/b'),

                        Forms\Components\DatePicker::make('appointment_date')
                            ->label('Tanggal TMT Jabatan Kepala Sekolah')
                            ->default('2021-07-15'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Menjabat Aktif')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Filosofi & Visi Kepemimpinan')
                    ->schema([
                        Forms\Components\Textarea::make('leadership_quote')
                            ->label('Filosofi Kepemimpinan Pembelajaran (Quote Utama)')
                            ->placeholder('Kutipan filosofi yang tampil di banner E-PKKS...')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('bio_summary')
                            ->label('Ringkasan Biografi & Visi Pengabdian')
                            ->placeholder('Deskripsi ringkas rekam jejak pengabdian kepala sekolah...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Foto Resmi & Tautan Dokumen Eksekutif (CV & Pakta Integritas)')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Foto Resmi Kepala Sekolah')
                            ->image()
                            ->disk('public')
                            ->directory('headmaster')
                            ->helperText('Format PNG/JPG, rasio kotak atau pas foto.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('cv_pdf_path')
                            ->label('Tautan Unduh Dokumen CV Lengkap (PDF / Google Drive)')
                            ->placeholder('https://drive.google.com/...')
                            ->url()
                            ->helperText('Tautan berkas CV kepala sekolah di Google Drive atau file lokal.'),

                        Forms\Components\TextInput::make('integrity_pact_path')
                            ->label('Tautan Dokumen Pakta Integritas (PDF / Google Drive)')
                            ->placeholder('https://drive.google.com/...')
                            ->url()
                            ->helperText('Tautan surat/dokumen komitmen Pakta Integritas di Google Drive atau file lokal.'),
                    ]),

                Forms\Components\Section::make('Riwayat Pengembangan Diri, Prestasi & Organisasi')
                    ->schema([
                        Forms\Components\Repeater::make('histories')
                            ->relationship('histories')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Kategori')
                                    ->options([
                                        'education' => '🎓 Pendidikan Formal',
                                        'training' => '📚 Diklat & Pelatihan',
                                        'achievement' => '🏆 Prestasi & Penghargaan',
                                        'organization' => '🤝 Organisasi Profesi',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('title')
                                    ->label('Nama Kegiatan / Gelar / Penghargaan')
                                    ->required(),

                                Forms\Components\TextInput::make('organizer')
                                    ->label('Penyelenggara / Institusi'),

                                Forms\Components\TextInput::make('year')
                                    ->label('Tahun / Periode'),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(asset('images/headmaster-default.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kepala Sekolah')
                    ->fontFamily('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->fontFamily('mono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rank_group')
                    ->label('Pangkat/Golongan'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('TMT Jabatan')
                    ->date('d M Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeadmasterProfiles::route('/'),
            'create' => Pages\CreateHeadmasterProfile::route('/create'),
            'edit' => Pages\EditHeadmasterProfile::route('/{record}/edit'),
        ];
    }
}
