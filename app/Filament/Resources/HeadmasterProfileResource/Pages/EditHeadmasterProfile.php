<?php

namespace App\Filament\Resources\HeadmasterProfileResource\Pages;

use App\Filament\Resources\HeadmasterProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeadmasterProfile extends EditRecord
{
    protected static string $resource = HeadmasterProfileResource::class;

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
