<?php

namespace App\Filament\Resources\KantorCabangs;

use App\Filament\Resources\KantorCabangs\Pages\CreateKantorCabang;
use App\Filament\Resources\KantorCabangs\Pages\EditKantorCabang;
use App\Filament\Resources\KantorCabangs\Pages\ListKantorCabangs;
use App\Filament\Resources\KantorCabangs\Schemas\KantorCabangForm;
use App\Filament\Resources\KantorCabangs\Tables\KantorCabangsTable;
use App\Models\KantorCabang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KantorCabangResource extends Resource
{
    protected static ?string $model = KantorCabang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Kantor Cabang';
    protected static ?string $pluralModelLabel = 'Kantor Cabang';

    public static function form(Schema $schema): Schema
    {
        return KantorCabangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KantorCabangsTable::configure($table);
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
            'index' => ListKantorCabangs::route('/'),
            'create' => CreateKantorCabang::route('/create'),
            'edit' => EditKantorCabang::route('/{record}/edit'),
        ];
    }
}
