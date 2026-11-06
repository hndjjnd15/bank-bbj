<?php

namespace App\Filament\Resources\PengurusPerusahaans\Tables;

use App\Models\PengurusPerusahaan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengurusPerusahaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')->searchable(),
                TextColumn::make('jabatan')->label('Jabatan'),
                TextColumn::make('kelompok')
                    ->label('Kelompok')
                    ->badge()
                    ->formatStateUsing(fn ($state) => PengurusPerusahaan::KELOMPOK[$state] ?? $state),
                TextColumn::make('urutan')->label('Urutan')->sortable(),
            ])
            ->defaultSort('urutan')
            ->filters([
                SelectFilter::make('kelompok')->options(PengurusPerusahaan::KELOMPOK),
            ])
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
