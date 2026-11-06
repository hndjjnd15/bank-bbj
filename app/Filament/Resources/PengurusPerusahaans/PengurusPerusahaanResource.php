<?php

namespace App\Filament\Resources\PengurusPerusahaans;

use App\Filament\Resources\PengurusPerusahaans\Pages\CreatePengurusPerusahaan;
use App\Filament\Resources\PengurusPerusahaans\Pages\EditPengurusPerusahaan;
use App\Filament\Resources\PengurusPerusahaans\Pages\ListPengurusPerusahaans;
use App\Filament\Resources\PengurusPerusahaans\Schemas\PengurusPerusahaanForm;
use App\Filament\Resources\PengurusPerusahaans\Tables\PengurusPerusahaansTable;
use App\Models\PengurusPerusahaan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengurusPerusahaanResource extends Resource
{
    protected static ?string $model = PengurusPerusahaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Pengurus';
    protected static ?string $pluralModelLabel = 'Pengurus';
    public static function form(Schema $schema): Schema
    {
        return PengurusPerusahaanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengurusPerusahaansTable::configure($table);
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
            'index' => ListPengurusPerusahaans::route('/'),
            'create' => CreatePengurusPerusahaan::route('/create'),
            'edit' => EditPengurusPerusahaan::route('/{record}/edit'),
        ];
    }
}
