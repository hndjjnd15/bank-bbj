<?php

namespace App\Filament\Resources\SukuBungaDepositos\Pages;

use App\Filament\Resources\SukuBungaDepositos\SukuBungaDepositoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSukuBungaDepositos extends ListRecords
{
    protected static string $resource = SukuBungaDepositoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
