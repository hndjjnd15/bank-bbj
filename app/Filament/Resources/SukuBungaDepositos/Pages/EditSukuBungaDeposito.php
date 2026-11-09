<?php

namespace App\Filament\Resources\SukuBungaDepositos\Pages;

use App\Filament\Resources\SukuBungaDepositos\SukuBungaDepositoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSukuBungaDeposito extends EditRecord
{
    protected static string $resource = SukuBungaDepositoResource::class;

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
