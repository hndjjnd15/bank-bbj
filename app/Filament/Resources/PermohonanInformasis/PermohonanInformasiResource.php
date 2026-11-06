<?php

namespace App\Filament\Resources\PermohonanInformasis;

use App\Filament\Resources\PermohonanInformasis\Pages\CreatePermohonanInformasi;
use App\Filament\Resources\PermohonanInformasis\Pages\EditPermohonanInformasi;
use App\Filament\Resources\PermohonanInformasis\Pages\ListPermohonanInformasis;
use App\Filament\Resources\PermohonanInformasis\Schemas\PermohonanInformasiForm;
use App\Filament\Resources\PermohonanInformasis\Tables\PermohonanInformasisTable;
use App\Models\PermohonanInformasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermohonanInformasiResource extends Resource
{
    protected static ?string $model = PermohonanInformasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Permohonan Informasi';
    protected static ?string $pluralModelLabel = 'Permohonan Informasi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PermohonanInformasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermohonanInformasisTable::configure($table);
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
            'index' => ListPermohonanInformasis::route('/'),
            'create' => CreatePermohonanInformasi::route('/create'),
            'edit' => EditPermohonanInformasi::route('/{record}/edit'),
        ];
    }
}
