<?php

namespace App\Filament\Resources\SukuBungaDepositos\Pages;

use App\Filament\Resources\SukuBungaDepositos\SukuBungaDepositoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSukuBungaDeposito extends CreateRecord
{
    protected static string $resource = SukuBungaDepositoResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
