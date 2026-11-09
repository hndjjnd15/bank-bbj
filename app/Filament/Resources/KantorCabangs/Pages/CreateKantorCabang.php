<?php

namespace App\Filament\Resources\KantorCabangs\Pages;

use App\Filament\Resources\KantorCabangs\KantorCabangResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKantorCabang extends CreateRecord
{
    protected static string $resource = KantorCabangResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
