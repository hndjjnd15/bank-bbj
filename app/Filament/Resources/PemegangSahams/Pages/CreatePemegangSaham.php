<?php

namespace App\Filament\Resources\PemegangSahams\Pages;

use App\Filament\Resources\PemegangSahams\PemegangSahamResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePemegangSaham extends CreateRecord
{
    protected static string $resource = PemegangSahamResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
