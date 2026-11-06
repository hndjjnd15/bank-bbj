<?php

namespace App\Filament\Resources\PemegangSahams;

use App\Filament\Resources\PemegangSahams\Pages\CreatePemegangSaham;
use App\Filament\Resources\PemegangSahams\Pages\EditPemegangSaham;
use App\Filament\Resources\PemegangSahams\Pages\ListPemegangSahams;
use App\Filament\Resources\PemegangSahams\Schemas\PemegangSahamForm;
use App\Filament\Resources\PemegangSahams\Tables\PemegangSahamsTable;
use App\Models\PemegangSaham;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PemegangSahamResource extends Resource
{
    protected static ?string $model = PemegangSaham::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Pemegang Saham';
    protected static ?string $pluralModelLabel = 'Pemegang Saham';
    public static function form(Schema $schema): Schema
    {
        return PemegangSahamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PemegangSahamsTable::configure($table);
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
            'index' => ListPemegangSahams::route('/'),
            'create' => CreatePemegangSaham::route('/create'),
            'edit' => EditPemegangSaham::route('/{record}/edit'),
        ];
    }
}
