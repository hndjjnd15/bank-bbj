<?php

namespace App\Filament\Resources\PengurusPerusahaans\Pages;

use App\Filament\Resources\PengurusPerusahaans\PengurusPerusahaanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePengurusPerusahaan extends CreateRecord
{
    protected static string $resource = PengurusPerusahaanResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
