<?php

namespace App\Filament\Resources\KantorCabangs\Tables;

use App\Models\KantorCabang;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KantorCabangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Kantor')->searchable(),
                TextColumn::make('tipe')->label('Jenis')->badge(),
                TextColumn::make('kota')->label('Wilayah')->searchable(),
                TextColumn::make('telepon')->label('Telepon')->placeholder('-')->toggleable(),
                TextColumn::make('urutan')->label('Urutan')->sortable()->toggleable(),
                IconColumn::make('diterbitkan')->label('Tampil')->boolean(),
            ])
            ->defaultSort('urutan')
            ->filters([
                SelectFilter::make('tipe')->options(array_combine(KantorCabang::TIPE, KantorCabang::TIPE)),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/jaringan-kantor/' . $record->slug))
                    ->openUrlInNewTab(),
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
