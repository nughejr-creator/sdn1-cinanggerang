<?php

namespace App\Filament\Resources\HeadmasterProfileResource\Pages;

use App\Filament\Resources\HeadmasterProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeadmasterProfiles extends ListRecords
{
    protected static string $resource = HeadmasterProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Profil Pimpinan'),
        ];
    }
}
