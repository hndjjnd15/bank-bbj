<?php

namespace App\Filament\Resources\DokumenPpids\Pages;

use App\Filament\Resources\DokumenPpids\DokumenPpidResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenPpid extends CreateRecord
{
    protected static string $resource = DokumenPpidResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
