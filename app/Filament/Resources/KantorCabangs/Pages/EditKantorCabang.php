<?php

namespace App\Filament\Resources\KantorCabangs\Pages;

use App\Filament\Resources\KantorCabangs\KantorCabangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKantorCabang extends EditRecord
{
    protected static string $resource = KantorCabangResource::class;

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
