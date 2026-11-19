<?php

namespace App\Filament\Resources\VideoYoutubes\Pages;

use App\Filament\Resources\VideoYoutubes\VideoYoutubeResource;
use App\Models\VideoYoutube;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVideoYoutube extends CreateRecord
{
    protected static string $resource = VideoYoutubeResource::class;

    protected function beforeCreate(): void
    {
        if (VideoYoutube::query()->count() >= 5) {
            Notification::make()
                ->danger()
                ->title('Maksimal 5 video YouTube')
                ->body('Hapus salah satu video sebelum menambahkan yang baru.')
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
