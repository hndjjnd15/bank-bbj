<?php

namespace App\Filament\Resources\LaporanPelanggarans\Pages;

use App\Filament\Resources\LaporanPelanggarans\LaporanPelanggaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPelanggarans extends ListRecords
{
    protected static string $resource = LaporanPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
