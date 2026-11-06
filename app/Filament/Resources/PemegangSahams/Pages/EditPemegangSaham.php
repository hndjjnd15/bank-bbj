<?php

namespace App\Filament\Resources\PemegangSahams\Pages;

use App\Filament\Resources\PemegangSahams\PemegangSahamResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPemegangSaham extends EditRecord
{
    protected static string $resource = PemegangSahamResource::class;

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
