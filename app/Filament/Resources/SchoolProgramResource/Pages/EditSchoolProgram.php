<?php

namespace App\Filament\Resources\SchoolProgramResource\Pages;

use App\Filament\Resources\SchoolProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolProgram extends EditRecord
{
    protected static string $resource = SchoolProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
