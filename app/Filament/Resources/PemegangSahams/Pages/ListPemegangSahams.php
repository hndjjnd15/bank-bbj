<?php

namespace App\Filament\Resources\PemegangSahams\Pages;

use App\Filament\Resources\PemegangSahams\PemegangSahamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPemegangSahams extends ListRecords
{
    protected static string $resource = PemegangSahamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
