<?php

namespace App\Filament\Resources\SukuBungaDepositos\Tables;

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
            ->modifyQueryUsing(fn ($query) => $query->orderBy('tenor_bulan')->orderBy('nominal_min'))
            ->columns([
                TextColumn::make('tenor_bulan')->label('Jangka waktu')->suffix(' bulan'),
                TextColumn::make('label')->label('Rentang nominal'),
                // Suku bunga bisa diubah langsung di tabel (ketik angka lalu tekan Enter atau klik di luar kolom)
                TextInputColumn::make('bunga')
                    ->label('Suku bunga (% p.a.)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100']),
            ])
            ->filters([
                SelectFilter::make('tenor_bulan')
                    ->label('Jangka waktu')
                    ->options(fn () => SukuBungaDeposito::query()->distinct()->orderBy('tenor_bulan')->pluck('tenor_bulan', 'tenor_bulan')->map(fn ($t) => $t . ' bulan')->all()),
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
