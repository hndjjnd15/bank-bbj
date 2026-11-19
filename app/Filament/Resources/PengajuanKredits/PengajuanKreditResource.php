<?php

namespace App\Filament\Resources\PengajuanKredits;

use App\Filament\Resources\PengajuanKredits\Pages\EditPengajuanKredit;
use App\Filament\Resources\PengajuanKredits\Pages\ListPengajuanKredits;
use App\Filament\Resources\PengajuanKredits\Schemas\PengajuanKreditForm;
use App\Filament\Resources\PengajuanKredits\Tables\PengajuanKreditsTable;
use App\Models\PengajuanKredit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengajuanKreditResource extends Resource
{
    protected static ?string $model = PengajuanKredit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'Pengajuan Kredit';

    protected static ?string $pluralModelLabel = 'Pengajuan Kredit';

    // Pengajuan hanya masuk dari formulir publik, tidak dibuat dari admin
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PengajuanKreditForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengajuanKreditsTable::configure($table);
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
            'index' => ListPengajuanKredits::route('/'),
            'edit' => EditPengajuanKredit::route('/{record}/edit'),
        ];
    }
}
