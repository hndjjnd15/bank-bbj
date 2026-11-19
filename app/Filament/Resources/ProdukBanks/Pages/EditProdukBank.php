<?php

namespace App\Filament\Resources\ProdukBanks\Pages;

use App\Filament\Resources\ProdukBanks\ProdukBankResource;
use App\Support\HtmlDeskripsiProduk;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProdukBank extends EditRecord
{
    protected static string $resource = ProdukBankResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['jenis'] ?? null) === 'kredit') {
            $data['deskripsi'] = $data['deskripsi_kredit'] ?? null;
        } elseif ($this->record->jenis === 'kredit') {
            // Jenis diubah dari kredit ke tabungan/deposito: ubah HTML editor kaya
            // menjadi teks biasa agar tidak tampil sebagai tag di halaman publik.
            $data['deskripsi'] = HtmlDeskripsiProduk::teksBiasa($data['deskripsi'] ?? null);
        }

        unset($data['deskripsi_kredit']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // Setelah menyimpan perubahan, kembali ke daftar
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
