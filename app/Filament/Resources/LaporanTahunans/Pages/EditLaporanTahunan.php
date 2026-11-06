<?php

namespace App\Filament\Resources\LaporanTahunans\Pages;

use App\Filament\Resources\LaporanTahunans\LaporanTahunanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaporanTahunan extends EditRecord
{
    protected static string $resource = LaporanTahunanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // Setelah menyimpan perubahan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
