<?php

namespace App\Filament\Resources\PengaturanSitus\Pages;

use App\Filament\Resources\PengaturanSitus\PengaturanSitusResource;
use App\Models\PengaturanSitus;
use Filament\Resources\Pages\ListRecords;

class ListPengaturanSitus extends ListRecords
{
    protected static string $resource = PengaturanSitusResource::class;

    public function mount(): void
    {
        // Pengaturan situs hanya satu baris; buat otomatis saat menu dibuka.
        PengaturanSitus::saatIni();

        parent::mount();
    }
}
