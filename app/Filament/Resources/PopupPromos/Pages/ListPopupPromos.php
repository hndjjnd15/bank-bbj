<?php

namespace App\Filament\Resources\PopupPromos\Pages;

use App\Filament\Resources\PopupPromos\PopupPromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPopupPromos extends ListRecords
{
    protected static string $resource = PopupPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
