<?php

namespace App\Filament\Resources\LaporanPelanggarans;

use App\Filament\Resources\LaporanPelanggarans\Pages\CreateLaporanPelanggaran;
use App\Filament\Resources\LaporanPelanggarans\Pages\EditLaporanPelanggaran;
use App\Filament\Resources\LaporanPelanggarans\Pages\ListLaporanPelanggarans;
use App\Filament\Resources\LaporanPelanggarans\Schemas\LaporanPelanggaranForm;
use App\Filament\Resources\LaporanPelanggarans\Tables\LaporanPelanggaransTable;
use App\Models\LaporanPelanggaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanPelanggaranResource extends Resource
{
    protected static ?string $model = LaporanPelanggaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Laporan WBS';
    protected static ?string $pluralModelLabel = 'Laporan WBS';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return LaporanPelanggaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanPelanggaransTable::configure($table);
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
            'index' => ListLaporanPelanggarans::route('/'),
            'create' => CreateLaporanPelanggaran::route('/create'),
            'edit' => EditLaporanPelanggaran::route('/{record}/edit'),
        ];
    }
}
