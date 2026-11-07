<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\StudentClass;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'E-Learning (Pembelajaran)';
    protected static ?string $navigationLabel = 'Materi Belajar Siswa';
    protected static ?string $modelLabel = 'Materi Belajar';
    protected static ?string $pluralModelLabel = 'Materi Pembelajaran Siswa';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas & Mata Pelajaran')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('student_class_id')
                            ->label('Sasaran Kelas')
                            ->relationship('studentClass', 'nama')
                            ->required(),
                        Forms\Components\Select::make('course_id')
                            ->label('Mata Pelajaran')
                            ->relationship('course', 'nama')
                            ->required(),
                        Forms\Components\Select::make('teacher_id')
                            ->label('Guru Pengampu')
                            ->relationship('teacher', 'nama')
                            ->default(fn () => auth()->user()?->teacher_id)
                            ->searchable(),
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Materi Pembelajaran')
                            ->placeholder('Contoh: Perkalian Pecahan / Menyayangi Tumbuhan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi_ringkas')
                            ->label('Ringkasan / Tujuan Pembelajaran')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Isi & Media Pembelajaran')
                    ->schema([
                        Forms\Components\Select::make('tipe_materi')
                            ->label('Format Utama Materi')
                            ->options([
                                'Teks / Artikel' => 'Teks & Penjelasan Lengkap',
                                'Video YouTube' => 'Video Pembelajaran YouTube',
                                'Dokumen PDF / LKPD' => 'Lembar Kerja / PDF Buku Siswa',
                            ])
                            ->default('Teks / Artikel')
                            ->required(),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Tautan Video YouTube')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->url(),
                        Forms\Components\FileUpload::make('file_materi')
                            ->label('File LKPD / PDF Materi')
                            ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                            ->directory('elearning-materi')
                            ->openable()
                            ->downloadable(),
                        Forms\Components\RichEditor::make('konten')
                            ->label('Isi Materi Lengkap (Bisa sisipkan gambar/tabel)')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Terbitkan ke Siswa Sekarang')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('studentClass.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('tipe_materi')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('teacher.nama')
                    ->label('Guru')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Tayang')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('student_class_id')
                    ->label('Filter Kelas')
                    ->relationship('studentClass', 'nama'),
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Filter Mapel')
                    ->relationship('course', 'nama'),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
