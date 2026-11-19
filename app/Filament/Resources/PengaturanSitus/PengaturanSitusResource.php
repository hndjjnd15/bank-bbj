<?php

namespace App\Filament\Resources\PengaturanSitus;

use App\Filament\Resources\PengaturanSitus\Pages\EditPengaturanSitus;
use App\Filament\Resources\PengaturanSitus\Pages\ListPengaturanSitus;
use App\Filament\Resources\PengaturanSitus\Schemas\PengaturanSitusForm;
use App\Filament\Resources\PengaturanSitus\Tables\PengaturanSitusTable;
use App\Models\PengaturanSitus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengaturanSitusResource extends Resource
{
    protected static ?string $model = PengaturanSitus::class;

    protected static ?string $slug = 'pengaturan-situs';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $modelLabel = 'pengaturan situs';

    protected static ?string $pluralModelLabel = 'pengaturan situs';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    public static function form(Schema $schema): Schema
    {
        return PengaturanSitusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengaturanSitusTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPengaturanSitus::route('/'),
            'edit' => EditPengaturanSitus::route('/{record}/edit'),
        ];
    }
}
