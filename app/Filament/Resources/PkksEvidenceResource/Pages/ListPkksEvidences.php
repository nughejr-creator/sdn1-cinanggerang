<?php

namespace App\Filament\Resources\PkksEvidenceResource\Pages;

use App\Filament\Resources\PkksEvidenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPkksEvidences extends ListRecords
{
    protected static string $resource = PkksEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Upload Bukti Fisik Baru'),
        ];
    }
}
