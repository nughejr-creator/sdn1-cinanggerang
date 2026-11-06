<?php

namespace App\Filament\Resources\PkksIndicatorResource\Pages;

use App\Filament\Resources\PkksIndicatorResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePkksIndicator extends CreateRecord
{
    protected static string $resource = PkksIndicatorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
