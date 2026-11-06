<?php

namespace App\Filament\Resources\LaporanTriwulans\Pages;

use App\Filament\Resources\LaporanTriwulans\LaporanTriwulanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanTriwulan extends CreateRecord
{
    protected static string $resource = LaporanTriwulanResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
