<?php

namespace App\Filament\Resources\PermohonanInformasis\Tables;

use App\Models\PermohonanInformasi;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermohonanInformasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('No. registrasi')->searchable(),
                TextColumn::make('nama')->label('Pemohon')->searchable(),
                TextColumn::make('jenis_pemohon')->label('Jenis')->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => PermohonanInformasi::STATUS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'diproses' => 'warning',
                        'dipenuhi' => 'success',
                        'ditolak' => 'gray',
                        default => 'gray',
                    }),
                // Perkiraan batas tanggapan 10 hari kerja (Senin-Jumat; libur nasional belum dihitung).
                // Merah jika sudah lewat dan permohonan belum dituntaskan.
                TextColumn::make('batas_tanggapan')
                    ->label('Batas tanggapan')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->terlambat ? 'danger' : null)
                    ->weight(fn ($record) => $record->terlambat ? 'bold' : null)
                    ->description(fn ($record) => $record->terlambat ? 'Melewati batas' : null),
                TextColumn::make('catatan')
                    ->label('Catatan internal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->catatan)
                    ->placeholder('-')
                    ->wrap(),
                IconColumn::make('email_terkirim_at')->label('Email')->boolean(),
                IconColumn::make('wa_terkirim_at')->label('WA')->boolean(),
                TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(PermohonanInformasi::STATUS),
            ])
            ->recordActions([
                EditAction::make()->label('Tindak lanjut'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
