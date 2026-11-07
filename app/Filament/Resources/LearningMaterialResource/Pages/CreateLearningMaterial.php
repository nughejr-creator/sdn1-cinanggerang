<?php

namespace App\Filament\Resources\LearningMaterialResource\Pages;

use App\Filament\Resources\LearningMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningMaterial extends CreateRecord
{
    protected static string $resource = LearningMaterialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if (empty($data['teacher_id']) && auth()->user()?->teacher_id) {
            $data['teacher_id'] = auth()->user()->teacher_id;
        }
        return $data;
    }
}
