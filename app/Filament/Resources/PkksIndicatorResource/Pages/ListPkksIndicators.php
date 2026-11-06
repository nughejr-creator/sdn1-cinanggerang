<?php

namespace App\Filament\Resources\PkksIndicatorResource\Pages;

use App\Filament\Resources\PkksIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPkksIndicators extends ListRecords
{
    protected static string $resource = PkksIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Indikator'),
        ];
    }
}
