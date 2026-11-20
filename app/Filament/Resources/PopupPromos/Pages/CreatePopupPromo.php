<?php

namespace App\Filament\Resources\PopupPromos\Pages;

use App\Filament\Resources\PopupPromos\PopupPromoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePopupPromo extends CreateRecord
{
    protected static string $resource = PopupPromoResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
