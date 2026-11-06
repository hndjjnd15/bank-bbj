<?php

namespace App\Filament\Resources\ProfilPerusahaans\Pages;

use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use Filament\Resources\Pages\EditRecord;

class EditProfilPerusahaan extends EditRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    // Setelah menyimpan perubahan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
