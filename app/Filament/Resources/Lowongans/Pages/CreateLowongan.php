<?php

namespace App\Filament\Resources\Lowongans\Pages;

use App\Filament\Resources\Lowongans\LowonganResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLowongan extends CreateRecord
{
    protected static string $resource = LowonganResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
