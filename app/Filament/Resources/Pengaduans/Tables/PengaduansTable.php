<?php

namespace App\Filament\Resources\Pengaduans\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengaduansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('Kode tiket')->searchable(),
                TextColumn::make('nama')->label('Nama')->searchable(),
                TextColumn::make('jenis')->label('Jenis'),
                TextColumn::make('cabang')->label('Cabang')->toggleable(),
                TextColumn::make('kategori')->label('Kategori')->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                // Catatan internal dari tindak lanjut admin (arahkan kursor untuk membaca lengkap)
                TextColumn::make('catatan')
                    ->label('Catatan internal')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->catatan)
                    ->placeholder('-')
                    ->wrap(),
                // Centang hijau = notifikasi berhasil terkirim
                IconColumn::make('email_terkirim_at')->label('Email')->boolean(),
                IconColumn::make('wa_terkirim_at')->label('WA')->boolean(),
                TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'baru' => 'Baru',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                ]),
                SelectFilter::make('cabang')->options(
                    array_combine(config('pengaduan.cabang'), config('pengaduan.cabang'))
                ),
            ])
            ->recordActions([
                Action::make('lampiran')
                    ->label('Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->url(fn ($record) => route('pengaduan.lampiran', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->lampiran)),
                EditAction::make()->label('Tindak lanjut'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
