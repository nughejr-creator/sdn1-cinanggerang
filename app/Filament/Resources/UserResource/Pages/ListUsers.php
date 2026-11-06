<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Services\TeacherAccountGenerator;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateTeacherAccounts')
                ->label('⚡ Generate Akun Semua Guru')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Akun Guru Otomatis')
                ->modalDescription('Sistem akan membuatkan akun login secara otomatis untuk seluruh data GTK / Guru yang terdaftar di database. Password default yang disetel adalah: guru123456.')
                ->modalSubmitActionLabel('Ya, Generate Sekarang')
                ->action(function () {
                    $result = TeacherAccountGenerator::generateAll();

                    Notification::make()
                        ->title('Akun Guru Berhasil Disinkronkan!')
                        ->body("Berhasil memproses {$result['total']} guru ({$result['created']} akun baru dibuat, {$result['updated']} akun ditautkan). Password login: {$result['default_password']}")
                        ->success()
                        ->duration(8000)
                        ->send();
                }),

            Actions\CreateAction::make()->label('Tambah Pengguna Baru'),
        ];
    }
}
