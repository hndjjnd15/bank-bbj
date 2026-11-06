<?php

namespace App\Filament\Resources\LaporanTriwulans\Pages;

use App\Filament\Resources\LaporanTriwulans\LaporanTriwulanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaporanTriwulan extends EditRecord
{
    protected static string $resource = LaporanTriwulanResource::class;

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
