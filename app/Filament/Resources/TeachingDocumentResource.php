<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingDocumentResource\Pages;
use App\Models\TeachingDocument;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeachingDocumentResource extends Resource
{
    protected static ?string $model = TeachingDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    protected static ?string $navigationGroup = 'E-Learning (Pembelajaran)';
    protected static ?string $navigationLabel = 'Bank Perangkat Ajar & Modul';
    protected static ?string $modelLabel = 'Perangkat Pembelajaran';
    protected static ?string $pluralModelLabel = 'Bank Perangkat Ajar & Modul Guru';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Perangkat Ajar (Kurikulum Merdeka)')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->label('Nama Guru Penyusun')
                            ->relationship('teacher', 'nama')
                            ->default(fn () => auth()->user()?->teacher_id)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('tingkat_kelas')
                            ->label('Fase / Tingkat Kelas')
                            ->options([
                                1 => 'Kelas I (Fase A)',
                                2 => 'Kelas II (Fase A)',
                                3 => 'Kelas III (Fase B)',
                                4 => 'Kelas IV (Fase B)',
                                5 => 'Kelas V (Fase C)',
                                6 => 'Kelas VI (Fase C)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('course_id')
                            ->label('Mata Pelajaran')
                            ->relationship('course', 'nama')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Pilih mata pelajaran atau pilih Semua Mapel')
                            ->helperText('Pilih mapel spesifik, atau pilih "Semua Mata Pelajaran" jika berkas (seperti TP/ATP/Modul) memuat seluruh mapel satu kelas.'),
                        Forms\Components\TextInput::make('tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->default('2025/2026')
                            ->required(),
                        Forms\Components\Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'Ganjil' => 'Semester Ganjil',
                                'Genap' => 'Semester Genap',
                                'Ganjil & Genap' => 'Ganjil & Genap (1 Tahun Penuh)',
                            ])
                            ->default('Ganjil')
                            ->required(),
                        Forms\Components\Select::make('jenis_dokumen')
                            ->label('Jenis Perangkat / Berkas')
                            ->options([
                                'Modul Ajar / RPP Merdeka' => 'Modul Ajar / RPP Merdeka',
                                'Capaian Pembelajaran (CP)' => 'Capaian Pembelajaran (CP)',
                                'Alur Tujuan Pembelajaran (ATP)' => 'Alur Tujuan Pembelajaran (ATP)',
                                'Tujuan Pembelajaran (TP)' => 'Tujuan Pembelajaran (TP)',
                                'Modul Kokurikuler / Proyek P5' => 'Modul Kokurikuler / Proyek P5',
                                'Program Tahunan (Prota) & Promes' => 'Program Tahunan (Prota) & Promes',
                                'Lembar Kerja Peserta Didik (LKPD)' => 'Lembar Kerja Peserta Didik (LKPD)',
                                'Kisi-kisi & Instrumen Asesmen' => 'Kisi-kisi & Instrumen Asesmen',
                                'Bank Soal / Naskah Ujian' => 'Bank Soal / Naskah Ujian',
                                'Kriteria Ketercapaian TP (KKTP)' => 'Kriteria Ketercapaian TP (KKTP)',
                                'Bahan Ajar / Media Presentasi' => 'Bahan Ajar / Slide PPT / Media',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('judul_perangkat')
                            ->label('Judul Perangkat / Pokok Bahasan')
                            ->placeholder('Contoh: Modul Ajar IPAS Bab 1 Tumbuhan Sumber Kehidupan / Bank Soal STS 1')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Keterangan / Ruang Lingkup Materi')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Unggah Berkas Dokumen')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('file_dokumen')
                            ->label('Upload File Dokumen (PDF, Word, Excel, PPT)')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            ])
                            ->directory('perangkat-ajar')
                            ->openable()
                            ->downloadable()
                            ->helperText('Dukungan: Dokumen PDF, Word (docx), Excel, PowerPoint.'),
                        Forms\Components\TextInput::make('link_gdrive')
                            ->label('Tautan Google Drive Folder Perangkat (Opsional)')
                            ->placeholder('https://drive.google.com/drive/folders/...')
                            ->url()
                            ->helperText('Gunakan jika perangkat berupa kumpulan folder lengkap di Google Drive.'),
                        Forms\Components\Toggle::make('is_shared')
                            ->label('Bagikan Dokumen Ini (Bisa Dilihat & Diunduh Rekan Guru Lain)')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Telaah & Supervisi Kepala Sekolah (Indikator PKKS No. 9)')
                    ->description('Penilaian kesesuaian dokumen perangkat ajar kurikulum merdeka oleh Kepala Sekolah')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status_telaah')
                            ->label('Status Hasil Telaah Kepala Sekolah')
                            ->options([
                                'Draf / Belum Ditelaah' => 'Draf / Belum Ditelaah',
                                'Sudah Ditelaah & Disetujui' => 'Sudah Ditelaah & Disetujui (Sesuai Kurikulum)',
                                'Perlu Perbaikan / Revisi' => 'Perlu Perbaikan / Revisi',
                            ])
                            ->default('Draf / Belum Ditelaah')
                            ->disabled(fn () => auth()->user()?->role === 'Guru')
                            ->helperText(fn () => auth()->user()?->role === 'Guru' ? 'Status telaah diisi oleh Kepala Sekolah saat supervisi.' : null)
                            ->required(),
                        Forms\Components\Textarea::make('catatan_kepala_sekolah')
                            ->label('Catatan Hasil Telaah Kepala Sekolah')
                            ->placeholder('Catatan umpan balik perbaikan dari Bpk. Kepala Sekolah Aep Yayat saat supervisi perangkat.')
                            ->disabled(fn () => auth()->user()?->role === 'Guru')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat_kelas')
                    ->label('Kelas')
                    ->formatStateUsing(fn ($state) => "Kelas {$state}")
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_dokumen')
                    ->label('Jenis Perangkat')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_perangkat')
                    ->label('Judul Perangkat')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('teacher.nama')
                    ->label('Penyusun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.nama')
                    ->label('Mata Pelajaran')
                    ->default('Semua Mata Pelajaran')
                    ->badge()
                    ->color(fn ($state) => str_contains($state ?? '', 'Semua') ? 'success' : 'gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_telaah')
                    ->label('Status Telaah')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Sudah Ditelaah & Disetujui' => 'success',
                        'Perlu Perbaikan / Revisi' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\IconColumn::make('file_dokumen')
                    ->label('File')
                    ->boolean()
                    ->state(fn ($record) => !empty($record->file_dokumen))
                    ->trueIcon('heroicon-o-arrow-down-tray')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('tingkat_kelas')
                    ->label('Filter Kelas')
                    ->options([
                        1 => 'Kelas I',
                        2 => 'Kelas II',
                        3 => 'Kelas III',
                        4 => 'Kelas IV',
                        5 => 'Kelas V',
                        6 => 'Kelas VI',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_dokumen')
                    ->label('Filter Jenis Perangkat')
                    ->options([
                        'Modul Ajar / RPP Merdeka' => 'Modul Ajar / RPP Merdeka',
                        'Capaian Pembelajaran (CP)' => 'Capaian Pembelajaran (CP)',
                        'Alur Tujuan Pembelajaran (ATP)' => 'Alur Tujuan Pembelajaran (ATP)',
                        'Modul Kokurikuler / Proyek P5' => 'Modul Kokurikuler / Proyek P5',
                        'Bank Soal / Naskah Ujian' => 'Bank Soal / Naskah Ujian',
                        'Kisi-kisi & Instrumen Asesmen' => 'Kisi-kisi & Instrumen Asesmen',
                    ]),
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Filter Guru')
                    ->relationship('teacher', 'nama'),
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
            'index' => Pages\ListTeachingDocuments::route('/'),
            'create' => Pages\CreateTeachingDocument::route('/create'),
            'edit' => Pages\EditTeachingDocument::route('/{record}/edit'),
        ];
    }
}
