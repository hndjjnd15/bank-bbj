<?php

namespace App\Filament\Resources\PengajuanKredits\Tables;

use App\Models\PengajuanKredit;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengajuanKreditsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('Nomor tiket')->searchable(),
                TextColumn::make('nama_lengkap')->label('Nama')->searchable(),
                TextColumn::make('nama_produk')->label('Produk')->toggleable(),
                TextColumn::make('nominal_kredit')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('no_hp')->label('No. HP')->toggleable(),
                TextColumn::make('email')->label('Email')->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                // Centang hijau = notifikasi berhasil terkirim
                IconColumn::make('email_terkirim_at')->label('Email')->boolean(),
                IconColumn::make('wa_terkirim_at')->label('WA')->boolean(),
                TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(PengajuanKredit::STATUS),
                SelectFilter::make('jenis_kredit')
                    ->label('Jenis kredit')
                    ->options(config('pengajuan-kredit.produk')),
            ])
            ->recordActions([
                Action::make('unduh_berkas')
                    ->label('Dokumen (ZIP)')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->url(fn ($record) => route('pengajuan-kredit.berkas-zip', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->berkas)),
                EditAction::make()->label('Tindak lanjut'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
