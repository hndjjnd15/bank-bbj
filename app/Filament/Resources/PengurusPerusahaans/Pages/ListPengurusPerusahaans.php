<?php

namespace App\Filament\Resources\PengurusPerusahaans\Pages;

use App\Filament\Resources\PengurusPerusahaans\PengurusPerusahaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengurusPerusahaans extends ListRecords
{
    protected static string $resource = PengurusPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
