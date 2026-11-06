<?php

namespace App\Filament\Resources\LaporanTahunans\Pages;

use App\Filament\Resources\LaporanTahunans\LaporanTahunanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanTahunans extends ListRecords
{
    protected static string $resource = LaporanTahunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
