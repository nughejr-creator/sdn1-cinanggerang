<?php

namespace App\Filament\Resources\SchoolProgramResource\Pages;

use App\Filament\Resources\SchoolProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchoolPrograms extends ListRecords
{
    protected static string $resource = SchoolProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Program Baru'),
        ];
    }
}
