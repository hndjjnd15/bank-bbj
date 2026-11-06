<?php

namespace App\Filament\Resources\Lelangs\Tables;

use App\Models\Lelang;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LelangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->label('Jaminan')->searchable()->limit(50),
                TextColumn::make('tipe_aset')->label('Tipe'),
                TextColumn::make('kota')->label('Kota')->searchable(),
                TextColumn::make('harga_limit')
                    ->label('Harga limit')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Lelang::STATUS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'lelang' => 'danger',
                        'ayda' => 'success',
                        'terjual' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('jadwal_lelang')->label('Jadwal')->dateTime('d M Y H:i')->placeholder('-')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(Lelang::STATUS),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/lelang/' . $record->slug))
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
