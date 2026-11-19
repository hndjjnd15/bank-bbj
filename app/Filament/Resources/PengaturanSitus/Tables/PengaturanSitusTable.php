<?php

namespace App\Filament\Resources\PengaturanSitus\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengaturanSitusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('whatsapp')
                    ->label('Nomor WhatsApp')
                    ->placeholder('Belum diisi'),

                TextColumn::make('whatsapp_aktif')
                    ->label('Tombol mengambang')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Tampil' : 'Disembunyikan')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

                TextColumn::make('updated_at')
                    ->label('Terakhir diubah')
                    ->dateTime('d M Y H:i'),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah pengaturan'),
            ]);
    }
}
