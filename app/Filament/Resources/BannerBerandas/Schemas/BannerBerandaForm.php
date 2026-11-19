<?php

namespace App\Filament\Resources\BannerBerandas\Schemas;

use App\Models\BannerBeranda;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerBerandaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('judul')
                    ->label('Judul banner')
                    ->required()
                    ->maxLength(160)
                    ->columnSpanFull(),

                Textarea::make('teks')
                    ->label('Teks pendamping')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),

                TextInput::make('alt')
                    ->label('Teks alternatif gambar')
                    ->helperText('Jelaskan isi gambar untuk pembaca layar.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                FileUpload::make('gambar_desktop')
                    ->label('Gambar desktop')
                    ->helperText('Gambar utama untuk layar desktop. Maksimal 10 MB.')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->disk('public')
                    ->directory('banner-beranda')
                    ->visibility('public')
                    ->required(),

                FileUpload::make('gambar_mobile')
                    ->label('Gambar ponsel (opsional)')
                    ->helperText('Gunakan gambar potret untuk layar ponsel. Jika kosong, gambar desktop dipakai.')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->disk('public')
                    ->directory('banner-beranda')
                    ->visibility('public'),

                TextInput::make('durasi_detik')
                    ->label('Durasi tampil')
                    ->helperText('Lama slide ditampilkan sebelum beralih.')
                    ->numeric()
                    ->rule('integer')
                    ->minValue(3)
                    ->maxValue(30)
                    ->suffix('detik')
                    ->default(6)
                    ->required(),

                TextInput::make('urutan')
                    ->label('Urutan tampil')
                    ->numeric()
                    ->rule('integer')
                    ->minValue(0)
                    ->default(fn (): int => ((int) BannerBeranda::query()->max('urutan')) + 1)
                    ->required(),

                Toggle::make('aktif')
                    ->label('Tampilkan di beranda')
                    ->helperText('Banner yang nonaktif tetap tersimpan di admin, tetapi tidak muncul di situs.')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }
}
