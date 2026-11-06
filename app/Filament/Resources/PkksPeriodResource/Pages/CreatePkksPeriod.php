<?php

namespace App\Filament\Resources\PkksPeriodResource\Pages;

use App\Filament\Resources\PkksPeriodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePkksPeriod extends CreateRecord
{
    protected static string $resource = PkksPeriodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
