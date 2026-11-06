<?php

namespace App\Filament\Resources\PengurusPerusahaans\Pages;

use App\Filament\Resources\PengurusPerusahaans\PengurusPerusahaanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengurusPerusahaan extends EditRecord
{
    protected static string $resource = PengurusPerusahaanResource::class;

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
