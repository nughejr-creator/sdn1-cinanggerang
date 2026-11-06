<?php

namespace App\Filament\Resources\SchoolHolidayResource\Pages;

use App\Filament\Resources\SchoolHolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchoolHolidays extends ListRecords
{
    protected static string $resource = SchoolHolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Hari Libur'),
        ];
    }
}
