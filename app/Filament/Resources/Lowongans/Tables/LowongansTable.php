<?php

namespace App\Filament\Resources\Lowongans\Tables;

use App\Models\Lowongan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LowongansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->label('Posisi')->searchable()->limit(45),
                TextColumn::make('penempatan')->label('Penempatan')->searchable(),
                TextColumn::make('tipe_kerja')->label('Tipe'),
                TextColumn::make('batas_lamaran')->label('Batas lamaran')->date('d M Y')->placeholder('-')->sortable(),
                // Status dihitung otomatis: dimatikan admin atau batas lamaran sudah lewat = Ditutup
                TextColumn::make('sudah_ditutup')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Ditutup' : 'Dibuka')
                    ->color(fn ($state) => $state ? 'gray' : 'success'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('tipe_kerja')
                    ->label('Tipe pekerjaan')
                    ->options(array_combine(Lowongan::TIPE_KERJA, Lowongan::TIPE_KERJA)),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => url('/karir/' . $record->slug))
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
