<?php

namespace App\Filament\Resources\BannerBerandas\Pages;

use App\Filament\Resources\BannerBerandas\BannerBerandaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBannerBerandas extends ListRecords
{
    protected static string $resource = BannerBerandaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
