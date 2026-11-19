<?php

namespace App\Filament\Resources\BannerBerandas\Pages;

use App\Filament\Resources\BannerBerandas\BannerBerandaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBannerBeranda extends CreateRecord
{
    protected static string $resource = BannerBerandaResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
