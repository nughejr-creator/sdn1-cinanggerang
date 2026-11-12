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
                            ->fetchFileInformation(false)
                            ->columnSpanFull(),
                        \FilamentTiptapEditor\TiptapEditor::make('konten')
                            ->label('Isi Lengkap Berita (Editor Lengkap Mirip MS Word)')
                            ->helperText('💡 Tips Penulis: Jika menyalin teks dari Word/Web, gunakan Ctrl + Shift + V (Paste Teks Bersih) atau gunakan tombol ikon Penghapus (Clear Formatting) pada toolbar agar teks bebas dari kode asing dan responsif diformat.')
                            ->profile('default')
                            ->disk('public')
                            ->directory('posts-content')
                            ->maxContentWidth('5xl')
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

                Forms\Components\Section::make('Program Kegiatan Sekolah (Opsional)')
                    ->description('Hubungkan postingan ini dengan kegiatan Ekstrakurikuler, Kokurikuler, atau Pembiasaan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('program_category')
                            ->label('Kategori Program')
                            ->options([
                                'ekstrakurikuler' => 'Ekstrakurikuler (Pramuka, Olahraga, Seni, dll)',
                                'kokurikuler' => 'Kokurikuler (G7KAIH, Ramah Lingkungan, Zero Waste, dll)',
                                'pembiasaan' => 'Pembiasaan (Sholat Dhuha, Senam, Literasi, dll)',
                            ])
                            ->placeholder('Pilih kategori kegiatan...')
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('program_id', null)),

                        Forms\Components\Select::make('program_id')
                            ->label('Nama Kegiatan')
                            ->relationship('program', 'name', function (\Illuminate\Database\Eloquent\Builder $query, callable $get) {
                                $category = $get('program_category');
                                return $query->when($category, fn ($q) => $q->where('category', $category))
                                             ->where('is_active', true)
                                             ->orderBy('name');
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih atau cari kegiatan...')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kegiatan Baru')
                                    ->required()
                                    ->placeholder('Contoh: Robotik / Tari Jaipong'),
                                Forms\Components\Select::make('category')
                                    ->label('Kategori Kegiatan')
                                    ->options([
                                        'ekstrakurikuler' => 'Ekstrakurikuler',
                                        'kokurikuler' => 'Kokurikuler',
                                        'pembiasaan' => 'Pembiasaan',
                                    ])
                                    ->default(fn (callable $get) => $get('../../program_category') ?: 'ekstrakurikuler')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->rows(2),
                            ])
                            ->createOptionAction(function ($action) {
                                return $action
                                    ->modalHeading('Tambah Program Kegiatan Baru')
                                    ->modalSubmitActionLabel('Simpan & Pilih Langsung');
                            })
                            ->helperText('Klik tanda "+" untuk mendaftarkan kegiatan baru tanpa keluar dari form.'),
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
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program Kegiatan')
                    ->badge()
                    ->color(fn ($record) => match ($record->program_category ?: $record->program?->category) {
                        'ekstrakurikuler' => 'warning',
                        'kokurikuler' => 'success',
                        'pembiasaan' => 'info',
                        default => 'gray',
                    })
                    ->placeholder('-')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('program_category')
                    ->label('Kategori Program')
                    ->options([
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'kokurikuler' => 'Kokurikuler',
                        'pembiasaan' => 'Pembiasaan',
                    ]),
                Tables\Filters\SelectFilter::make('program_id')
                    ->label('Nama Program')
                    ->relationship('program', 'name'),
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
