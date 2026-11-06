<?php

namespace App\Filament\Resources\TeachingDocumentResource\Pages;

use App\Filament\Resources\TeachingDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachingDocuments extends ListRecords
{
    protected static string $resource = TeachingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Upload Perangkat Ajar Baru'),
        ];
    }
}
