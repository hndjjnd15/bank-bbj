<?php

namespace App\Filament\Resources\LaporanTahunans\Tables;

use App\Models\LaporanTahunan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanTahunansTable
{
    public static function configure(Table $table): Table
    {
        // Centang hijau = berkas sudah diunggah; silang = "Tidak Ada Data" di website
        $kolomBerkas = [];
        foreach (LaporanTahunan::KOLOM as $kolom => $label) {
            $kolomBerkas[] = IconColumn::make($kolom)->label($label)->boolean();
        }

        return $table
            ->columns([
                TextColumn::make('tahun')->label('Tahun')->sortable(),
                ...$kolomBerkas,
                TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tahun', 'desc')
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
