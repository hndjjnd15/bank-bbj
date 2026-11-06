<?php

namespace App\Filament\Resources\ProgramCsrs\Pages;

use App\Filament\Resources\ProgramCsrs\ProgramCsrResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgramCsr extends CreateRecord
{
    protected static string $resource = ProgramCsrResource::class;

    // Setelah menyimpan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
