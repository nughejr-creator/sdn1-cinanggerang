<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
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
            ->modalHeading('Pratinjau Warta / Berita')
            ->modalSubheading('Berikut adalah simulasi tampilan artikel seperti yang akan dilihat oleh pembaca:')
            ->modalWidth('5xl')
            ->modalContent(fn () => view('filament.components.post-preview', [
                'data' => $this->data,
                'record' => $this->record,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup Pratinjau');
    }
}
