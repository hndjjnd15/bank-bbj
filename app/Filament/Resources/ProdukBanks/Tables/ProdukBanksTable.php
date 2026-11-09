<?php

namespace App\Filament\Resources\ProdukBanks\Tables;

use App\Models\ProdukBank;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProdukBanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->label('Produk')->searchable(),
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProdukBank::JENIS[$state] ?? $state),
                TextColumn::make('setoran_minimal')
                    ->label('Setoran minimal')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '-'),
                IconColumn::make('brosur_pdf')->label('Brosur')->boolean(),
                IconColumn::make('diterbitkan')->label('Tampil')->boolean(),
            ])
            ->recordActions([
                // Halaman deposito sudah ada; tabungan dan kredit menyusul
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/' . $record->jenis))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->jenis === 'deposito'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
