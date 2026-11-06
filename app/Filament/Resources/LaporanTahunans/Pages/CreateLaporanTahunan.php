<?php

namespace App\Filament\Resources\LaporanTahunans\Pages;

use App\Filament\Resources\LaporanTahunans\LaporanTahunanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanTahunan extends CreateRecord
{
    protected static string $resource = LaporanTahunanResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
