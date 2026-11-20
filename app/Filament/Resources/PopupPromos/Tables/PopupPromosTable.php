<?php

namespace App\Filament\Resources\PopupPromos\Tables;

use App\Models\PopupPromo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PopupPromosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Pratinjau')
                    ->disk('public')
                    ->height(48),

                TextColumn::make('judul')
                    ->label('Nama')
                    ->searchable()
                    ->limit(45)
                    ->wrap(),

                TextColumn::make('tujuan_url')
                    ->label('Tautan tujuan')
                    ->limit(45)
                    ->url(fn (PopupPromo $record): string => $record->tujuan_url)
                    ->openUrlInNewTab(),

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
