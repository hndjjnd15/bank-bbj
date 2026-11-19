<?php

namespace App\Filament\Resources\PengaturanSitus\Pages;

use App\Filament\Resources\PengaturanSitus\PengaturanSitusResource;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanSitus extends EditRecord
{
    protected static string $resource = PengaturanSitusResource::class;

    // Setelah menyimpan perubahan, kembali ke daftar pengaturan
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
