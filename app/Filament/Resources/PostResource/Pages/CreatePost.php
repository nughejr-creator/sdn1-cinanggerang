<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
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
            ->modalHeading('Pratinjau Warta / Berita')
            ->modalSubheading('Berikut adalah simulasi tampilan artikel seperti yang akan dilihat oleh pembaca:')
            ->modalWidth('5xl')
            ->modalContent(fn () => view('filament.components.post-preview', [
                'data' => $this->data,
                'record' => null,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup Pratinjau');
    }
}
