<?php

namespace App\Filament\Resources\PpdbApplicantResource\Pages;

use App\Filament\Resources\PpdbApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPpdbApplicants extends ListRecords
{
    protected static string $resource = PpdbApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pendaftar Manual'),
        ];
    }
}
