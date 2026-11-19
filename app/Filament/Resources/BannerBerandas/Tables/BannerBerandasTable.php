<?php

namespace App\Filament\Resources\BannerBerandas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerBerandasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar_desktop')
                    ->label('Pratinjau')
                    ->disk('public')
                    ->square(),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('durasi_detik')
                    ->label('Durasi')
                    ->formatStateUsing(fn (int $state): string => $state.' detik')
                    ->sortable(),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('aktif')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Tampil' : 'Disembunyikan')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
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
