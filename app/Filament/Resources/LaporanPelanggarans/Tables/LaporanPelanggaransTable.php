<?php

namespace App\Filament\Resources\LaporanPelanggarans\Tables;

use App\Models\LaporanPelanggaran;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LaporanPelanggaransTable
{
    private const STATUS = [
        'baru' => 'Baru',
        'diverifikasi' => 'Diverifikasi',
        'ditindaklanjuti' => 'Ditindaklanjuti',
        'selesai' => 'Selesai',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('Kode')->searchable(),
                TextColumn::make('kategori')->label('Kategori')->wrap(),
                TextColumn::make('hubungan')->label('Hubungan')->toggleable(),
                TextColumn::make('lokasi')->label('Lokasi / unit')->placeholder('-')->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::STATUS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'diverifikasi' => 'warning',
                        'ditindaklanjuti' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                // Catatan internal dari tindak lanjut tim (arahkan kursor untuk membaca lengkap)
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
                SelectFilter::make('status')->options(self::STATUS),
                SelectFilter::make('kategori')->options(
                    array_combine(config('wbs.kategori'), config('wbs.kategori'))
                ),
            ])
            ->recordActions([
                Action::make('lampiran')
                    ->label('Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->url(fn ($record) => route('wbs.lampiran', $record))
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
