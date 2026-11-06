<?php

namespace App\Filament\Resources\Lelangs\Pages;

use App\Filament\Resources\Lelangs\LelangsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLelangs extends ListRecords
{
    protected static string $resource = LelangsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
