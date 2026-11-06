<?php

namespace App\Filament\Resources\Lelangs\Pages;

use App\Filament\Resources\Lelangs\LelangsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLelangs extends EditRecord
{
    protected static string $resource = LelangsResource::class;

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
