<?php

namespace App\Filament\Resources\TeachingDocumentResource\Pages;

use App\Filament\Resources\TeachingDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachingDocument extends EditRecord
{
    protected static string $resource = TeachingDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
