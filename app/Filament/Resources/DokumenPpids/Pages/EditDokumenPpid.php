<?php

namespace App\Filament\Resources\DokumenPpids\Pages;

use App\Filament\Resources\DokumenPpids\DokumenPpidResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDokumenPpid extends EditRecord
{
    protected static string $resource = DokumenPpidResource::class;

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
