<?php

namespace App\Filament\Resources\SukuBungaDepositos;

use App\Filament\Resources\SukuBungaDepositos\Pages\CreateSukuBungaDeposito;
use App\Filament\Resources\SukuBungaDepositos\Pages\EditSukuBungaDeposito;
use App\Filament\Resources\SukuBungaDepositos\Pages\ListSukuBungaDepositos;
use App\Filament\Resources\SukuBungaDepositos\Schemas\SukuBungaDepositoForm;
use App\Filament\Resources\SukuBungaDepositos\Tables\SukuBungaDepositosTable;
use App\Models\SukuBungaDeposito;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SukuBungaDepositoResource extends Resource
{
    protected static ?string $model = SukuBungaDeposito::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Suku Bunga Deposito';
    protected static ?string $pluralModelLabel = 'Suku Bunga Deposito';

    public static function form(Schema $schema): Schema
    {
        return SukuBungaDepositoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SukuBungaDepositosTable::configure($table);
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
            'index' => ListSukuBungaDepositos::route('/'),
            'create' => CreateSukuBungaDeposito::route('/create'),
            'edit' => EditSukuBungaDeposito::route('/{record}/edit'),
        ];
    }
}
