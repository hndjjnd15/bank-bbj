<?php

namespace App\Filament\Resources\ProgramCsrs\Pages;

use App\Filament\Resources\ProgramCsrs\ProgramCsrResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramCsrs extends ListRecords
{
    protected static string $resource = ProgramCsrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
