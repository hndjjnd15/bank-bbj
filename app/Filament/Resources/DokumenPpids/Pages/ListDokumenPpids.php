<?php

namespace App\Filament\Resources\DokumenPpids\Pages;

use App\Filament\Resources\DokumenPpids\DokumenPpidResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDokumenPpids extends ListRecords
{
    protected static string $resource = DokumenPpidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
