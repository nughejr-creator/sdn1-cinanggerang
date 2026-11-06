<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoGalleryResource\Pages;
use App\Models\VideoGallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class VideoGalleryResource extends Resource
{
    protected static ?string $model = VideoGallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Publikasi & Informasi';
    protected static ?string $navigationLabel = 'Galeri Video (YT / TikTok / Drive)';
    protected static ?string $modelLabel = 'Galeri Video';
    protected static ?string $pluralModelLabel = 'Galeri Video Sekolah';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi & Sumber Video')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Video / Dokumentasi')
                            ->placeholder('Contoh: Penampilan Tari Jaipong Siswa / Video Profil Sekolah')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('platform')
                            ->label('Platform Sumber')
                            ->options([
                                'YouTube' => 'YouTube',
                                'TikTok' => 'TikTok',
                                'Google Drive' => 'Google Drive',
                                'Lainnya' => 'Tautan Langsung / Lainnya',
                            ])
                            ->default('YouTube')
                            ->required(),
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori Video')
                            ->options([
                                'Dokumentasi Kegiatan' => 'Dokumentasi Kegiatan Sekolah',
                                'E-PKKS / Eviden Mutu' => 'E-PKKS / Eviden Mutu Kepala Sekolah',
                                'Pembelajaran / E-Learning' => 'Video Pembelajaran Guru',
                                'Gelar Karya P5' => 'Gelar Karya P5 / Kurikulum Merdeka',
                                'Profil Sekolah' => 'Profil Satuan Pendidikan',
                            ])
                            ->default('Dokumentasi Kegiatan')
                            ->required(),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Tautan Video Lengkap')
                            ->placeholder('Paste link dari YouTube (https://youtu.be/...), TikTok, atau Google Drive')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Video / Acara')
                            ->default(now()),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan Video Ini di Halaman Utama Web')
                            ->default(true),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Ringkas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Video')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'YouTube' => 'danger',
                        'TikTok' => 'gray',
                        'Google Drive' => 'info',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan (Beranda)')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'YouTube' => 'YouTube',
                        'TikTok' => 'TikTok',
                        'Google Drive' => 'Google Drive',
                    ]),
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Dokumentasi Kegiatan' => 'Dokumentasi Kegiatan',
                        'E-PKKS / Eviden Mutu' => 'E-PKKS / Eviden Mutu',
                        'Pembelajaran / E-Learning' => 'Pembelajaran / E-Learning',
                        'Gelar Karya P5' => 'Gelar Karya P5',
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
            'index' => Pages\ListVideoGalleries::route('/'),
            'create' => Pages\CreateVideoGallery::route('/create'),
            'edit' => Pages\EditVideoGallery::route('/{record}/edit'),
        ];
    }
}
