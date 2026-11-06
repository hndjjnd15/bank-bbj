<?php

namespace App\Filament\Resources\DokumenPpids\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DokumenPpidsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->label('Judul')->searchable()->limit(60),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => config('ppid.kategori')[$state] ?? $state),
                TextColumn::make('tahun')->label('Tahun')->sortable(),
                IconColumn::make('file')->label('Berkas')->boolean(),
                IconColumn::make('diterbitkan')->label('Tampil')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tahun', 'desc')
            ->filters([
                SelectFilter::make('kategori')->options(config('ppid.kategori')),
                SelectFilter::make('tahun')->options(
                    fn () => \App\Models\DokumenPpid::query()->distinct()->orderByDesc('tahun')->pluck('tahun', 'tahun')->all()
                ),
            ])
            ->recordActions([
                Action::make('unduh')
                    ->label('Berkas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->file_url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->file)),
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
