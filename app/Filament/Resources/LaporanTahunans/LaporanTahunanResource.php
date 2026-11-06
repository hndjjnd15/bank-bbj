<?php

namespace App\Filament\Resources\LaporanTahunans;

use App\Filament\Resources\LaporanTahunans\Pages\CreateLaporanTahunan;
use App\Filament\Resources\LaporanTahunans\Pages\EditLaporanTahunan;
use App\Filament\Resources\LaporanTahunans\Pages\ListLaporanTahunans;
use App\Filament\Resources\LaporanTahunans\Schemas\LaporanTahunanForm;
use App\Filament\Resources\LaporanTahunans\Tables\LaporanTahunansTable;
use App\Models\LaporanTahunan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanTahunanResource extends Resource
{
    protected static ?string $model = LaporanTahunan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Laporan Tahunan';
    protected static ?string $pluralModelLabel = 'Laporan Tahunan';

    public static function form(Schema $schema): Schema
    {
        return LaporanTahunanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanTahunansTable::configure($table);
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
            'index' => ListLaporanTahunans::route('/'),
            'create' => CreateLaporanTahunan::route('/create'),
            'edit' => EditLaporanTahunan::route('/{record}/edit'),
        ];
    }
}
