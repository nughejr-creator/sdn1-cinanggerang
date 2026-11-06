<?php

namespace App\Filament\Resources\HeadmasterProfileResource\Pages;

use App\Filament\Resources\HeadmasterProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHeadmasterProfile extends CreateRecord
{
    protected static string $resource = HeadmasterProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
