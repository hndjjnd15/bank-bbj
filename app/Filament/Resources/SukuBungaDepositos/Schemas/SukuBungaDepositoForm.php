<?php

namespace App\Filament\Resources\SukuBungaDepositos\Schemas;

use App\Models\ProdukBank;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SukuBungaDepositoForm
{
    private static function produkOptions(): array
    {
        return ProdukBank::query()
            ->whereIn('jenis', ['deposito', 'tabungan'])
            ->orderBy('jenis')
            ->orderBy('judul')
            ->get()
            ->mapWithKeys(fn (ProdukBank $produk): array => [
                $produk->id => ProdukBank::JENIS[$produk->jenis].' — '.$produk->judul,
            ])
            ->all();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('produk_bank_id')
                    ->label('Produk')
                    ->options(self::produkOptions())
                    ->searchable()
                    ->required()
                    ->live(),

                TextInput::make('tenor_bulan')
                    ->label('Tenor (bulan)')
                    ->helperText('Contoh: 1, 3, 6, 12, 24.')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(120)
                    ->suffix('bulan')
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule, $get) => $rule->where('produk_bank_id', $get('produk_bank_id')),
                    )
                    ->validationMessages(['unique' => 'Suku bunga untuk produk dan tenor ini sudah tersedia.']),

                TextInput::make('bunga')
                    ->label('Suku Bunga per Tahun')
                    ->helperText('Persen per tahun (p.a.). Gunakan titik untuk desimal, contoh: 4.75')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('% p.a.'),
            ]);
    }
}
