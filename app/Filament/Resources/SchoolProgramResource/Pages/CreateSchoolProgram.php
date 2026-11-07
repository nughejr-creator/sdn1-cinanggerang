<?php

namespace App\Filament\Resources\SchoolProgramResource\Pages;

use App\Filament\Resources\SchoolProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolProgram extends CreateRecord
{
    protected static string $resource = SchoolProgramResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
