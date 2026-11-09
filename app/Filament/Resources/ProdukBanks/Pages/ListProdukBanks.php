<?php

namespace App\Filament\Resources\ProdukBanks\Pages;

use App\Filament\Resources\ProdukBanks\ProdukBankResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProdukBanks extends ListRecords
{
    protected static string $resource = ProdukBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
