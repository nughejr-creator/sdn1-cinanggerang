<?php

namespace App\Filament\Resources\PkksIndicatorResource\Pages;

use App\Filament\Resources\PkksIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPkksIndicator extends EditRecord
{
    protected static string $resource = PkksIndicatorResource::class;

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
