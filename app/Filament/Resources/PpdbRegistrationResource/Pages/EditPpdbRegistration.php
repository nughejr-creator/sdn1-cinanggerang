<?php

namespace App\Filament\Resources\PpdbRegistrationResource\Pages;

use App\Filament\Resources\PpdbRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpdbRegistration extends EditRecord
{
    protected static string $resource = PpdbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetakKelulusan')
                ->label('Cetak Surat Keterangan Kelulusan')
                ->icon('heroicon-m-printer')
                ->color('success')
                ->visible(fn () => $this->record->status_pendaftaran === 'Diterima')
                ->url(fn () => route('ppdb.print-kelulusan', $this->record->registration_number), shouldOpenInNewTab: true),

            Actions\DeleteAction::make(),
        ];
    }
}
