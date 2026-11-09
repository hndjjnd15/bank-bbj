<?php

namespace App\Filament\Resources\SukuBungaDepositos\Schemas;

use App\Models\SukuBungaDeposito;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class SukuBungaDepositoForm
{
    // Teks pratinjau, contoh: "Preview: Rp 100.000.000"
    private static function preview($state): ?string
    {
        $angka = preg_replace('/\D+/', '', (string) $state);

        return $angka !== '' ? 'Preview: Rp ' . number_format((float) $angka, 0, ',', '.') : null;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenor_bulan')
                    ->label('Jangka Waktu (bulan)')
                    ->helperText('Contoh: 1, 3, 6, 12, 24.')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(120)
                    ->suffix('bulan'),

                TextInput::make('bunga')
                    ->label('Suku Bunga per Tahun')
                    ->helperText('Persen per tahun (p.a.). Gunakan titik untuk desimal, contoh: 4.75')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('% p.a.'),

                TextInput::make('nominal_min')
                    ->label('Nominal Mulai Dari (Rp)')
                    ->helperText('Batas bawah, termasuk. Contoh: 5.000.000')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->live(onBlur: true)
                    ->hint(fn ($state) => self::preview($state)),

                TextInput::make('nominal_maks')
                    ->label('Nominal Sampai (Rp)')
                    ->helperText('Batas atas, TIDAK termasuk. Contoh: 100.000.000 berarti kurang dari 100 juta. Kosongkan jika tanpa batas atas.')
                    ->numeric()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                    ->stripCharacters('.')
                    ->live(onBlur: true)
                    ->hint(fn ($state) => self::preview($state)),
            ]);
    }
}
