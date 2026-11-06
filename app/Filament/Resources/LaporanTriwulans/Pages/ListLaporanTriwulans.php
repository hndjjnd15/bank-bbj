<?php

namespace App\Filament\Resources\LaporanTriwulans\Pages;

use App\Filament\Resources\LaporanTriwulans\LaporanTriwulanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanTriwulans extends ListRecords
{
    protected static string $resource = LaporanTriwulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
