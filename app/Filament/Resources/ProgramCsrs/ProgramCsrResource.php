<?php

namespace App\Filament\Resources\ProgramCsrs;

use App\Filament\Resources\ProgramCsrs\Pages\CreateProgramCsr;
use App\Filament\Resources\ProgramCsrs\Pages\EditProgramCsr;
use App\Filament\Resources\ProgramCsrs\Pages\ListProgramCsrs;
use App\Filament\Resources\ProgramCsrs\Schemas\ProgramCsrForm;
use App\Filament\Resources\ProgramCsrs\Tables\ProgramCsrsTable;
use App\Models\ProgramCsr;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramCsrResource extends Resource
{
    protected static ?string $model = ProgramCsr::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Program CSR';
    protected static ?string $pluralModelLabel = 'Program CSR';

    public static function form(Schema $schema): Schema
    {
        return ProgramCsrForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramCsrsTable::configure($table);
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
            'index' => ListProgramCsrs::route('/'),
            'create' => CreateProgramCsr::route('/create'),
            'edit' => EditProgramCsr::route('/{record}/edit'),
        ];
    }
}
