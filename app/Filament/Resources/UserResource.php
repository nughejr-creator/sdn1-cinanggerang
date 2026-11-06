<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pengaturan Sekolah';
    protected static ?string $navigationLabel = 'Manajemen Pengguna & Akun';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna & Hak Akses';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun Pengguna')
                    ->description('Kelola akun login untuk Admin, Asisten Admin, maupun Akun Guru')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Contoh: Agi Hamdani / Operator Sekolah')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email (Untuk Login)')
                            ->placeholder('Contoh: agi@sdncinanggerang1.sch.id')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi (Password)')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText(fn (string $context): string => $context === 'edit' ? 'Kosongkan jika tidak ingin mengubah password.' : 'Minimal 6 karakter.'),

                        Forms\Components\Select::make('role')
                            ->label('Peran / Hak Akses (Role)')
                            ->options([
                                'Admin' => 'Super Admin (Kontrol Penuh)',
                                'Kepala Sekolah' => 'Kepala Sekolah (Monitoring & Supervisi)',
                                'Asisten Admin' => 'Asisten Admin / Operator Sekolah',
                                'Guru' => 'Guru (Akses Perangkat Ajar & Pembelajaran)',
                            ])
                            ->default('Asisten Admin')
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('teacher_id')
                            ->label('Tautkan ke Data Guru (Opsional)')
                            ->relationship('teacher', 'nama')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih guru yang memiliki akun ini...')
                            ->helperText('Jika akun ini milik guru, pilih nama guru untuk menghubungkan perangkat ajar & jadwal mengajarnya.')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Akun Aktif (Bisa Login)')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Jika dinonaktifkan, pengguna tidak dapat masuk ke panel admin.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email Login')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->copyable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Hak Akses')
                    ->badge()
                    ->colors([
                        'danger' => 'Admin',
                        'primary' => 'Kepala Sekolah',
                        'warning' => 'Asisten Admin',
                        'success' => 'Guru',
                    ]),

                Tables\Columns\TextColumn::make('teacher.nama')
                    ->label('Guru Terhubung')
                    ->placeholder('Bukan Guru / Akun Umum')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        'Admin' => 'Admin',
                        'Asisten Admin' => 'Asisten Admin',
                        'Guru' => 'Guru',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Filter Status Aktif'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
