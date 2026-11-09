<?php

namespace App\Filament\Resources\KantorCabangs\Pages;

use App\Filament\Resources\KantorCabangs\KantorCabangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKantorCabangs extends ListRecords
{
    protected static string $resource = KantorCabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
