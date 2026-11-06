<?php

namespace App\Filament\Resources\PkksPeriodResource\Pages;

use App\Filament\Resources\PkksPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPkksPeriods extends ListRecords
{
    protected static string $resource = PkksPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Periode'),
        ];
    }
}
