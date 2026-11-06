<?php

namespace App\Filament\Resources\SchoolHolidayResource\Pages;

use App\Filament\Resources\SchoolHolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolHoliday extends EditRecord
{
    protected static string $resource = SchoolHolidayResource::class;

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
