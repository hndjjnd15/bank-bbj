<?php

namespace App\Filament\Resources\ProdukBanks\Pages;

use App\Filament\Resources\ProdukBanks\ProdukBankResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProdukBank extends CreateRecord
{
    protected static string $resource = ProdukBankResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
