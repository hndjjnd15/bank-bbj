<?php

namespace App\Filament\Resources\ProfilPerusahaans\Pages;

use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProfilPerusahaan extends CreateRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
