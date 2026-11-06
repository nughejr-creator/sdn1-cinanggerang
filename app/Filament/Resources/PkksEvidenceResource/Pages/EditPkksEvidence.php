<?php

namespace App\Filament\Resources\PkksEvidenceResource\Pages;

use App\Filament\Resources\PkksEvidenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPkksEvidence extends EditRecord
{
    protected static string $resource = PkksEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
