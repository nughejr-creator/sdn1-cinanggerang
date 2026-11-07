<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningMaterialResource\Pages;
use App\Models\LearningMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LearningMaterialResource extends Resource
{
    protected static ?string $model = LearningMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'E-Learning & Pembelajaran';
    protected static ?string $navigationLabel = 'Perangkat & Materi Ajar';
    protected static ?string $modelLabel = 'Perangkat Ajar';
    protected static ?string $pluralModelLabel = 'Perangkat & Materi Ajar';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Perangkat & Bahan Ajar')
                    ->description('Kelola bahan pembelajaran digital, modul ajar kurikulum merdeka, dan LKPD')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Perangkat / Materi Pembelajaran')
                            ->placeholder('Contoh: Modul Ajar IPAS Kelas 4 - Siklus Air dan Pelestarian Lingkungan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('mata_pelajaran')
                            ->label('Mata Pelajaran')
                            ->options([
                                'Pendidikan Agama Islam (PAI)' => 'Pendidikan Agama Islam (PAI)',
                                'Pendidikan Pancasila' => 'Pendidikan Pancasila',
                                'Bahasa Indonesia' => 'Bahasa Indonesia',
                                'Matematika' => 'Matematika',
                                'IPAS (Ilmu Pengetahuan Alam & Sosial)' => 'IPAS (Ilmu Pengetahuan Alam & Sosial)',
                                'PJOK' => 'PJOK',
                                'Seni Rupa' => 'Seni Rupa',
                                'Seni Musik' => 'Seni Musik',
                                'Seni Tari' => 'Seni Tari',
                                'Bahasa Sunda' => 'Bahasa Sunda (Muatan Lokal)',
                                'Bahasa Inggris' => 'Bahasa Inggris',
                                'P5 (Proyek Profil Pelajar Pancasila)' => 'P5 (Proyek Profil Pelajar Pancasila)',
                                'Tematik / Umum' => 'Tematik / Umum',
                            ])
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('kelas_fase')
                            ->label('Tingkat Kelas / Fase')
                            ->options([
                                'Kelas 1 (Fase A)' => 'Kelas 1 (Fase A)',
                                'Kelas 2 (Fase A)' => 'Kelas 2 (Fase A)',
                                'Kelas 3 (Fase B)' => 'Kelas 3 (Fase B)',
                                'Kelas 4 (Fase B)' => 'Kelas 4 (Fase B)',
                                'Kelas 5 (Fase C)' => 'Kelas 5 (Fase C)',
                                'Kelas 6 (Fase C)' => 'Kelas 6 (Fase C)',
                                'Semua Tingkat (Umum)' => 'Semua Tingkat (Umum)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('jenis_perangkat')
                            ->label('Kategori / Jenis Dokumen')
                            ->options([
                                'Modul Ajar' => 'Modul Ajar (Kurikulum Merdeka)',
                                'Bahan Tayang (Slide PPT)' => 'Bahan Tayang (Slide Presentasi / PPT)',
                                'LKPD (Lembar Kerja Siswa)' => 'LKPD (Lembar Kerja Siswa)',
                                'Rangkuman Materi' => 'Rangkuman & Ringkasan Materi',
                                'Video Pembelajaran' => 'Video / Media Pembelajaran Interaktif',
                                'Modul Proyek P5' => 'Modul / Panduan Proyek P5',
                                'Asesmen & Kisi-kisi' => 'Asesmen, Soal, & Kisi-kisi',
                            ])
                            ->default('Modul Ajar')
                            ->required(),

                        Forms\Components\Select::make('teacher_id')
                            ->label('Guru Pengampu / Penyusun')
                            ->relationship('teacher', 'nama')
                            ->default(fn () => auth()->user()?->teacher_id)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Publikasi')
                            ->options([
                                'Publish' => 'Publish (Dapat Diakses Siswa & Guru)',
                                'Draft' => 'Draft (Arsip Pribadi)',
                            ])
                            ->default('Publish')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Capaian Pembelajaran (CP) / Petunjuk Belajar')
                            ->placeholder('Tuliskan ringkasan tujuan pembelajaran, capaian fase, atau petunjuk penggunaan materi...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Berkas & Media Pembelajaran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('file_dokumen')
                            ->label('Unggah Berkas Dokumen (PDF, Word, PPT, ZIP)')
                            ->disk('public')
                            ->directory('learning_materials')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/zip',
                                'application/x-zip-compressed',
                            ])
                            ->maxSize(25600) // 25 MB
                            ->openable()
                            ->downloadable()
                            ->helperText('Format didukung: PDF, DOCX, PPTX, ZIP. Maksimal 25 MB.')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('link_eksternal')
                            ->label('Tautan Eksternal / Video (Opsional)')
                            ->placeholder('https://youtube.com/... atau https://drive.google.com/...')
                            ->url()
                            ->helperText('Tautkan ke video YouTube, Google Drive sekolah, Canva, atau Quizizz.')
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Perangkat / Materi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->jenis_perangkat . ' • ' . ($record->teacher?->nama ?? 'Guru')),

                Tables\Columns\TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas_fase')
                    ->label('Tingkat / Fase')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_dokumen')
                    ->label('Berkas')
                    ->formatStateUsing(fn ($state) => $state ? '📄 Unduh File' : '-')
                    ->url(fn ($record) => $record->file_url, true)
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),

                Tables\Columns\TextColumn::make('link_eksternal')
                    ->label('Tautan')
                    ->formatStateUsing(fn ($state) => $state ? '🔗 Buka Link' : '-')
                    ->url(fn ($state) => $state, true)
                    ->color(fn ($state) => $state ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Publish',
                        'gray' => 'Draft',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('mata_pelajaran')
                    ->label('Filter Mata Pelajaran')
                    ->options([
                        'Pendidikan Agama Islam (PAI)' => 'PAI',
                        'Pendidikan Pancasila' => 'Pendidikan Pancasila',
                        'Bahasa Indonesia' => 'Bahasa Indonesia',
                        'Matematika' => 'Matematika',
                        'IPAS (Ilmu Pengetahuan Alam & Sosial)' => 'IPAS',
                        'PJOK' => 'PJOK',
                        'Seni Rupa' => 'Seni Rupa',
                        'Bahasa Sunda' => 'Bahasa Sunda',
                        'Bahasa Inggris' => 'Bahasa Inggris',
                        'P5 (Proyek Profil Pelajar Pancasila)' => 'P5',
                    ]),
                Tables\Filters\SelectFilter::make('kelas_fase')
                    ->label('Filter Kelas')
                    ->options([
                        'Kelas 1 (Fase A)' => 'Kelas 1 (Fase A)',
                        'Kelas 2 (Fase A)' => 'Kelas 2 (Fase A)',
                        'Kelas 3 (Fase B)' => 'Kelas 3 (Fase B)',
                        'Kelas 4 (Fase B)' => 'Kelas 4 (Fase B)',
                        'Kelas 5 (Fase C)' => 'Kelas 5 (Fase C)',
                        'Kelas 6 (Fase C)' => 'Kelas 6 (Fase C)',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_perangkat')
                    ->label('Filter Jenis')
                    ->options([
                        'Modul Ajar' => 'Modul Ajar',
                        'Bahan Tayang (Slide PPT)' => 'Slide PPT',
                        'LKPD (Lembar Kerja Siswa)' => 'LKPD',
                        'Rangkuman Materi' => 'Rangkuman',
                        'Video Pembelajaran' => 'Video',
                        'Modul Proyek P5' => 'Modul P5',
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningMaterials::route('/'),
            'create' => Pages\CreateLearningMaterial::route('/create'),
            'edit' => Pages\EditLearningMaterial::route('/{record}/edit'),
        ];
    }
}
