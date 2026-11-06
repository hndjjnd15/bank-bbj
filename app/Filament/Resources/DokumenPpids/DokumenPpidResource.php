<?php

namespace App\Filament\Resources\DokumenPpids;

use App\Filament\Resources\DokumenPpids\Pages\CreateDokumenPpid;
use App\Filament\Resources\DokumenPpids\Pages\EditDokumenPpid;
use App\Filament\Resources\DokumenPpids\Pages\ListDokumenPpids;
use App\Filament\Resources\DokumenPpids\Schemas\DokumenPpidForm;
use App\Filament\Resources\DokumenPpids\Tables\DokumenPpidsTable;
use App\Models\DokumenPpid;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DokumenPpidResource extends Resource
{
    protected static ?string $model = DokumenPpid::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    // DokumenPpidResource.php
    protected static ?string $modelLabel = 'Dokumen PPID';
    protected static ?string $pluralModelLabel = 'Dokumen PPID';

    public static function form(Schema $schema): Schema
    {
        return DokumenPpidForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenPpidsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDokumenPpids::route('/'),
            'create' => CreateDokumenPpid::route('/create'),
            'edit' => EditDokumenPpid::route('/{record}/edit'),
        ];
    }
}
