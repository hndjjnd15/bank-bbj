<?php

namespace App\Filament\Resources\Lowongans;

use App\Filament\Resources\Lowongans\Pages\CreateLowongan;
use App\Filament\Resources\Lowongans\Pages\EditLowongan;
use App\Filament\Resources\Lowongans\Pages\ListLowongans;
use App\Filament\Resources\Lowongans\Schemas\LowonganForm;
use App\Filament\Resources\Lowongans\Tables\LowongansTable;
use App\Models\Lowongan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LowonganResource extends Resource
{
    protected static ?string $model = Lowongan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Lowongan';
    protected static ?string $pluralModelLabel = 'Lowongan';

    public static function form(Schema $schema): Schema
    {
        return LowonganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LowongansTable::configure($table);
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
            'index' => ListLowongans::route('/'),
            'create' => CreateLowongan::route('/create'),
            'edit' => EditLowongan::route('/{record}/edit'),
        ];
    }
}
