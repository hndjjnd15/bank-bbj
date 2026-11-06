<?php

namespace App\Filament\Resources\LaporanTriwulans;

use App\Filament\Resources\LaporanTriwulans\Pages\CreateLaporanTriwulan;
use App\Filament\Resources\LaporanTriwulans\Pages\EditLaporanTriwulan;
use App\Filament\Resources\LaporanTriwulans\Pages\ListLaporanTriwulans;
use App\Filament\Resources\LaporanTriwulans\Schemas\LaporanTriwulanForm;
use App\Filament\Resources\LaporanTriwulans\Tables\LaporanTriwulansTable;
use App\Models\LaporanTriwulan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanTriwulanResource extends Resource
{
    protected static ?string $model = LaporanTriwulan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Laporan Triwulan';
    protected static ?string $pluralModelLabel = 'Laporan Triwulan';

    protected static ?string $recordTitleAttribute = 'php artisan make:filament-resource LaporanTahunan';

    public static function form(Schema $schema): Schema
    {
        return LaporanTriwulanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanTriwulansTable::configure($table);
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
            'index' => ListLaporanTriwulans::route('/'),
            'create' => CreateLaporanTriwulan::route('/create'),
            'edit' => EditLaporanTriwulan::route('/{record}/edit'),
        ];
    }
}
