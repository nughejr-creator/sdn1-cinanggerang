<?php

namespace App\Filament\Resources\PkksPeriodResource\Pages;

use App\Filament\Resources\PkksPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPkksPeriod extends EditRecord
{
    protected static string $resource = PkksPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
