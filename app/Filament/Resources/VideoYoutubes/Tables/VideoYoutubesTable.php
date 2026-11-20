<?php

namespace App\Filament\Resources\VideoYoutubes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VideoYoutubesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->placeholder('Video Bank BBJ')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('url')
                    ->label('Tautan video')
                    ->url(fn ($record): string => $record->url)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable(),
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
