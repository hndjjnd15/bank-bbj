<?php

namespace App\Filament\Resources\SukuBungaDepositos\Tables;

use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SukuBungaDepositosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderBy('produk_bank_id')->orderBy('tenor_bulan'))
            ->columns([
                TextColumn::make('produk.judul')->label('Produk')->description(fn ($record) => ProdukBank::JENIS[$record->produk->jenis] ?? null)->searchable(),
                TextColumn::make('tenor_bulan')->label('Tenor')->suffix(' bulan'),
                // Suku bunga bisa diubah langsung di tabel (ketik angka lalu tekan Enter atau klik di luar kolom)
                TextInputColumn::make('bunga')
                    ->label('Suku bunga (% / tahun)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100']),
            ])
            ->filters([
                SelectFilter::make('produk_bank_id')
                    ->label('Produk')
                    ->options(fn () => ProdukBank::query()
                        ->whereIn('jenis', ['deposito', 'tabungan'])
                        ->orderBy('judul')
                        ->pluck('judul', 'id')
                        ->all()),
                SelectFilter::make('tenor_bulan')
                    ->label('Tenor')
                    ->options(fn () => SukuBungaDeposito::query()->distinct()->orderBy('tenor_bulan')->pluck('tenor_bulan', 'tenor_bulan')->map(fn ($tenor) => $tenor.' bulan')->all()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25, 50, 100]);
    }
}
