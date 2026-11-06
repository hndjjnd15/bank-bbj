<?php

namespace App\Filament\Resources\PemegangSahams\Tables;

use App\Models\PemegangSaham;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PemegangSahamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('No')->sortable(),
                TextColumn::make('nama')->label('Pemegang Saham')->searchable(),
                TextColumn::make('modal_dasar')->label('Modal Dasar')->formatStateUsing(fn ($state) => PemegangSaham::rupiah($state)),
                TextColumn::make('modal_disetor')->label('Modal Disetor')->formatStateUsing(fn ($state) => PemegangSaham::rupiah($state)),
            ])
            ->defaultSort('urutan')
            ->recordActions([
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
