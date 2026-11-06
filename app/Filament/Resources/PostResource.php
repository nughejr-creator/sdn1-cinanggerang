<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Publikasi & Informasi';
    protected static ?string $navigationLabel = 'Berita & Artikel';
    protected static ?string $modelLabel = 'Artikel Berita';
    protected static ?string $pluralModelLabel = 'Berita & Artikel';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Berita')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Berita / Artikel')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Tautan Ramah SEO (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'Berita Sekolah' => 'Berita Sekolah',
                                'Prestasi Siswa' => 'Prestasi Siswa',
                                'Artikel Pendidikan' => 'Artikel Pendidikan',
                                'Kegiatan Kurikulum Merdeka' => 'Kegiatan Kurikulum Merdeka',
                            ])
                            ->default('Berita Sekolah')
                            ->required(),
                        Forms\Components\DatePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now()),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Foto Utama (Thumbnail)')
                            ->image()
                            ->disk('public')
                            ->directory('posts-thumbnails')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('konten')
                            ->label('Isi Lengkap Berita (Dukungan Gambar & Format Teks)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Draft' => 'Draft (Simpan Sementara)',
                                'Publish' => 'Publish (Tayang ke Publik)',
                            ])
                            ->default('Publish')
                            ->required(),
                        Forms\Components\Toggle::make('tampil_di_beranda')
                            ->label('Sematkan ke Halaman Beranda Utama')
                            ->helperText('Aktifkan agar artikel ini menjadi sorotan utama di kolom Warta Beranda (maksimal 2 artikel).')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Berita')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Publish' ? 'success' : 'gray'),
                Tables\Columns\IconColumn::make('tampil_di_beranda')
                    ->label('Di Beranda')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tgl Tayang')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Berita Sekolah' => 'Berita Sekolah',
                        'Prestasi Siswa' => 'Prestasi Siswa',
                        'Artikel Pendidikan' => 'Artikel Pendidikan',
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
