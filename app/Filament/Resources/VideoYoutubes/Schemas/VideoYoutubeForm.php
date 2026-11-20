<?php

namespace App\Filament\Resources\VideoYoutubes\Schemas;

use App\Models\VideoYoutube;
use App\Rules\YoutubeVideoUrl;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoYoutubeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('judul')
                    ->label('Judul video')
                    ->helperText('Opsional. Tampil sebagai judul di daftar putar beranda.')
                    ->maxLength(150),

                TextInput::make('url')
                    ->label('Tautan video YouTube')
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->helperText('Tempel tautan video YouTube. Maksimal 5 video ditampilkan di beranda.')
                    ->required()
                    ->url()
                    ->maxLength(2048)
                    ->rule(new YoutubeVideoUrl),

                Hidden::make('urutan')
                    ->default(fn (): int => ((int) VideoYoutube::query()->max('urutan')) + 1),
            ]);
    }
}
