<?php

namespace App\Filament\Resources\Beritas\Pages;

use App\Filament\Resources\Beritas\BeritaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    // Setelah menyimpan, kembali ke daftar berita
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
