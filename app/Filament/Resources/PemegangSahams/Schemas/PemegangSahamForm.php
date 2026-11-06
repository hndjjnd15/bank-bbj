<?php

namespace App\Filament\Resources\PemegangSahams\Schemas;

use App\Models\PemegangSaham;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PemegangSahamForm
{
    // Teks pratinjau, contoh: "Rp 36.720.000.000"
    private static function preview($state): string
    {
        $angka = preg_replace('/\D+/', '', (string) $state);

        return $angka !== '' ? 'Preview: ' . PemegangSaham::rupiah($angka) : 'Ketik angka saja, titik pemisah ribuan muncul otomatis.';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Pemegang Saham')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                TextInput::make('modal_dasar')
                    ->label('Modal Dasar (Rp)')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->live(onBlur: true)
                    ->helperText(fn ($state) => self::preview($state)),

                TextInput::make('modal_disetor')
                    ->label('Nilai Modal Disetor (Rp)')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->live(onBlur: true)
                    ->helperText(fn ($state) => self::preview($state)),

                TextInput::make('urutan')
                    ->label('Urutan tampil')
                    ->helperText('Angka kecil tampil lebih dulu. Persentase kepemilikan dan modal disetor dihitung otomatis dari dua nilai di atas.')
                    ->numeric()
                    ->default(0)
                    ->columnSpanFull(),
            ]);
    }
}
