<?php

namespace App\Filament\Resources\PengajuanKredits\Pages;

use App\Filament\Resources\PengajuanKredits\PengajuanKreditResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanKredit extends EditRecord
{
    protected static string $resource = PengajuanKreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduh_berkas')
                ->label('Unduh semua dokumen (ZIP)')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->url(fn () => route('pengajuan-kredit.berkas-zip', $this->record))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->record->berkas)),
            DeleteAction::make(),
        ];
    }

    // Setelah tindak lanjut disimpan, kembali ke daftar pengajuan
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
