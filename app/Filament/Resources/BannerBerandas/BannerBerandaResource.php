<?php

namespace App\Filament\Resources\BannerBerandas;

use App\Filament\Resources\BannerBerandas\Pages\CreateBannerBeranda;
use App\Filament\Resources\BannerBerandas\Pages\EditBannerBeranda;
use App\Filament\Resources\BannerBerandas\Pages\ListBannerBerandas;
use App\Filament\Resources\BannerBerandas\Schemas\BannerBerandaForm;
use App\Filament\Resources\BannerBerandas\Tables\BannerBerandasTable;
use App\Models\BannerBeranda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BannerBerandaResource extends Resource
{
    protected static ?string $model = BannerBeranda::class;

    protected static ?string $slug = 'banner-beranda';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'banner beranda';

    protected static ?string $pluralModelLabel = 'banner beranda';

    protected static ?string $navigationLabel = 'Banner Beranda';

    public static function form(Schema $schema): Schema
    {
        return BannerBerandaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannerBerandasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBannerBerandas::route('/'),
            'create' => CreateBannerBeranda::route('/create'),
            'edit' => EditBannerBeranda::route('/{record}/edit'),
        ];
    }
}
