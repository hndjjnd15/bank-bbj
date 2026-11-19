<?php

namespace App\Filament\Resources\VideoYoutubes;

use App\Filament\Resources\VideoYoutubes\Pages\CreateVideoYoutube;
use App\Filament\Resources\VideoYoutubes\Pages\EditVideoYoutube;
use App\Filament\Resources\VideoYoutubes\Pages\ListVideoYoutubes;
use App\Filament\Resources\VideoYoutubes\Schemas\VideoYoutubeForm;
use App\Filament\Resources\VideoYoutubes\Tables\VideoYoutubesTable;
use App\Models\VideoYoutube;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VideoYoutubeResource extends Resource
{
    protected static ?string $model = VideoYoutube::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $modelLabel = 'Video YouTube';

    protected static ?string $pluralModelLabel = 'Video YouTube';

    public static function form(Schema $schema): Schema
    {
        return VideoYoutubeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoYoutubesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoYoutubes::route('/'),
            'create' => CreateVideoYoutube::route('/create'),
            'edit' => EditVideoYoutube::route('/{record}/edit'),
        ];
    }
}
