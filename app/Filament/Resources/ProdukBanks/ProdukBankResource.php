<?php

namespace App\Filament\Resources\ProdukBanks;

use App\Filament\Resources\ProdukBanks\Pages\CreateProdukBank;
use App\Filament\Resources\ProdukBanks\Pages\EditProdukBank;
use App\Filament\Resources\ProdukBanks\Pages\ListProdukBanks;
use App\Filament\Resources\ProdukBanks\Schemas\ProdukBankForm;
use App\Filament\Resources\ProdukBanks\Tables\ProdukBanksTable;
use App\Models\ProdukBank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdukBankResource extends Resource
{
    protected static ?string $model = ProdukBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Produk Bank';
    protected static ?string $pluralModelLabel = 'Produk Bank';

    public static function form(Schema $schema): Schema
    {
        return ProdukBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProdukBanksTable::configure($table);
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
            'index' => ListProdukBanks::route('/'),
            'create' => CreateProdukBank::route('/create'),
            'edit' => EditProdukBank::route('/{record}/edit'),
        ];
    }
}
