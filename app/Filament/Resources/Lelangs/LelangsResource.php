<?php

namespace App\Filament\Resources\Lelangs;

use App\Filament\Resources\Lelangs\Pages\CreateLelangs;
use App\Filament\Resources\Lelangs\Pages\EditLelangs;
use App\Filament\Resources\Lelangs\Pages\ListLelangs;
use App\Filament\Resources\Lelangs\Schemas\LelangForm;
use App\Filament\Resources\Lelangs\Tables\LelangsTable;
use App\Models\Lelang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LelangsResource extends Resource
{
    protected static ?string $model = Lelang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Lelang & AYDA';
    protected static ?string $pluralModelLabel = 'Lelang & AYDA';

    public static function form(Schema $schema): Schema
    {
        return LelangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LelangsTable::configure($table);
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
            'index' => ListLelangs::route('/'),
            'create' => CreateLelangs::route('/create'),
            'edit' => EditLelangs::route('/{record}/edit'),
        ];
    }
}
