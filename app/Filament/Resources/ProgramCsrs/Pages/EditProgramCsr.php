<?php

namespace App\Filament\Resources\ProgramCsrs\Pages;

use App\Filament\Resources\ProgramCsrs\ProgramCsrResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramCsr extends EditRecord
{
    protected static string $resource = ProgramCsrResource::class;

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
