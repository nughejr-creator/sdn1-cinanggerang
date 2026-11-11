<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAnnouncement extends EditRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getPreviewAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getPreviewAction(),
            Actions\DeleteAction::make(),
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
                'record' => $this->record,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup Pratinjau');
    }
}
