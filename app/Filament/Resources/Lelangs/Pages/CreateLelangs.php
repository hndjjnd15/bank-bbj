<?php

namespace App\Filament\Resources\Lelangs\Pages;

use App\Filament\Resources\Lelangs\LelangsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLelangs extends CreateRecord
{
    protected static string $resource = LelangsResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
