<?php

namespace App\Filament\Resources\VideoYoutubes\Pages;

use App\Filament\Resources\VideoYoutubes\VideoYoutubeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVideoYoutube extends EditRecord
{
    protected static string $resource = VideoYoutubeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
