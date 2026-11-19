<?php

namespace App\Filament\Resources\VideoYoutubes\Pages;

use App\Filament\Resources\VideoYoutubes\VideoYoutubeResource;
use App\Models\VideoYoutube;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoYoutubes extends ListRecords
{
    protected static string $resource = VideoYoutubeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => VideoYoutube::query()->count() < 5),
        ];
    }
}
