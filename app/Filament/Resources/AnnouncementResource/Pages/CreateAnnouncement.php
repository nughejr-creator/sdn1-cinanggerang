<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getPreviewAction(),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getPreviewAction(),
        ];
    }

    public function getPreviewAction(): Action
    {
        return Action::make('preview')
            ->label('Pratinjau (Preview)')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->modalHeading('Pratinjau Pengumuman Kedinasan')
            ->modalSubheading('Berikut adalah simulasi tampilan pengumuman seperti yang akan dilihat oleh pengunjung website:')
            ->modalWidth('5xl')
            ->modalContent(fn () => view('filament.components.announcement-preview', [
                'data' => $this->data,
                'record' => null,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup Pratinjau');
    }
}
