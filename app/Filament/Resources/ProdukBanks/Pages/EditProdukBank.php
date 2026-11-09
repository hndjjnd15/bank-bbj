<?php

namespace App\Filament\Resources\ProdukBanks\Pages;

use App\Filament\Resources\ProdukBanks\ProdukBankResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProdukBank extends EditRecord
{
    protected static string $resource = ProdukBankResource::class;

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
