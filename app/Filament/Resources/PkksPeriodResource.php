<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PkksPeriodResource\Pages;
use App\Models\PkksPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PkksPeriodResource extends Resource
{
    protected static ?string $model = PkksPeriod::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'E-PKKS (Dokumen Mutu)';
    protected static ?string $navigationLabel = 'Periode, Arsip & PIN Asesor';
    protected static ?string $modelLabel = 'Periode & Arsip PKKS';
    protected static ?string $pluralModelLabel = 'Pengaturan Periode & Arsip PKKS';
    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konfigurasi Periode & Siklus Arsip')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('academic_year')
                            ->label('Tahun Penilaian PKKS')
                            ->default('2026')
                            ->placeholder('Contoh: 2026')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Siklus Periode')
                            ->options([
                                'active' => '🟢 Aktif (Sedang Berjalan & Tampil Utama)',
                                'archived' => '🔒 Diarsipkan (Terkunci untuk Akreditasi BAN-S/M)',
                                'draft' => '📝 Draf (Persiapan Dokumen)',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('assessor_pin')
                            ->label('PIN Cepat Asesor (Token Pembuka Dokumen)')
                            ->default('1972')
                            ->helperText('PIN ini digunakan Tim Asesor untuk membuka dokumen privat di halaman /e-pkks.')
                            ->required(),

                        Forms\Components\TextInput::make('assessor_name')
                            ->label('Nama Tim Asesor / Pengawas Pembina')
                            ->placeholder('Tim Asesor Pengawas Pembina Disdik Kab. Sumedang'),

                        Forms\Components\DatePicker::make('assessment_date')
                            ->label('Tanggal Pelaksanaan Asesmen'),

                        Forms\Components\DateTimePicker::make('archived_at')
                            ->label('Waktu Diarsipkan (Terkunci)')
                            ->disabled(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Tandai Sebagai Periode Berjalan')
                            ->default(true),

                        Forms\Components\Textarea::make('theme')
                            ->label('Tema / Slogan Asesmen Kinerja')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Tahun PKKS')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Siklus')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'active' => 'success',
                        'archived' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active' => '🟢 Aktif Berjalan',
                        'archived' => '🔒 Terkunci (Arsip)',
                        default => '📝 Draf',
                    }),

                Tables\Columns\TextColumn::make('assessor_pin')
                    ->label('PIN Asesor')
                    ->fontFamily('mono')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('assessor_name')
                    ->label('Asesor / Pengawas')
                    ->wrap(),

                Tables\Columns\TextColumn::make('assessment_date')
                    ->label('Tgl Asesmen')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('archived_at')
                    ->label('Diarsipkan Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
            ])
            ->defaultSort('academic_year', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),

                // Aksi Kloning ke Tahun Baru
                Tables\Actions\Action::make('clone')
                    ->label('Kloning Tahun Baru')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Mulai Periode PKKS Tahun Baru')
                    ->modalDescription('Tahun ini otomatis diarsipkan (read-only), dan sistem akan membuat periode baru beserta 12 indikator resmi yang otomatis disalin.')
                    ->form([
                        Forms\Components\TextInput::make('new_year')
                            ->label('Tahun Penilaian Baru')
                            ->placeholder('Contoh: 2027')
                            ->required(),
                        Forms\Components\TextInput::make('assessor_name')
                            ->label('Nama Pengawas / Asesor Baru')
                            ->default('Tim Pengawas Pembina Disdik Kab. Sumedang'),
                        Forms\Components\TextInput::make('assessor_pin')
                            ->label('PIN Cepat Asesor Baru')
                            ->default('1972')
                            ->required(),
                    ])
                    ->action(function (PkksPeriod $record, array $data): void {
                        $record->cloneToNewYear(
                            $data['new_year'],
                            $data['assessor_name'] ?? null,
                            $data['assessor_pin'] ?? null
                        );

                        Notification::make()
                            ->title('Periode PKKS ' . $data['new_year'] . ' Berhasil Dibuat')
                            ->body('Struktur 12 indikator berhasil disalin, dan periode ' . $record->academic_year . ' telah resmi diarsipkan.')
                            ->success()
                            ->send();
                    }),

                // Aksi Jadikan Aktif
                Tables\Actions\Action::make('activate')
                    ->label('Set Aktif')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PkksPeriod $record) => $record->status !== 'active')
                    ->requiresConfirmation()
                    ->action(function (PkksPeriod $record): void {
                        $record->activate();

                        Notification::make()
                            ->title('Periode ' . $record->academic_year . ' Sekarang Aktif')
                            ->success()
                            ->send();
                    }),

                // Aksi Kunci & Arsipkan
                Tables\Actions\Action::make('archive')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (PkksPeriod $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (PkksPeriod $record): void {
                        $record->archive();

                        Notification::make()
                            ->title('Periode ' . $record->academic_year . ' Telah Diarsipkan')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPkksPeriods::route('/'),
            'create' => Pages\CreatePkksPeriod::route('/create'),
            'edit' => Pages\EditPkksPeriod::route('/{record}/edit'),
        ];
    }
}
