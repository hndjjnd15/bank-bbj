<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Models\Faq;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanya')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('halaman')
                    ->label('Penempatan')
                    ->formatStateUsing(fn (string $state): string => Faq::HALAMAN[$state] ?? $state)
                    ->badge()
                    ->sortable(),

                TextColumn::make('produk.judul')
                    ->label('Produk kredit')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('diterbitkan')
                    ->label('Tayang')
                    ->boolean(),
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
