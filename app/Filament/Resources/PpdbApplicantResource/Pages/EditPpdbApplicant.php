<?php

namespace App\Filament\Resources\PpdbApplicantResource\Pages;

use App\Filament\Resources\PpdbApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpdbApplicant extends EditRecord
{
    protected static string $resource = PpdbApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
