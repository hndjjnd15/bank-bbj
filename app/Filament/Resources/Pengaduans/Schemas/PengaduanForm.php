<?php

namespace App\Filament\Resources\Pengaduans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengaduanForm
{
    // Admin hanya mengubah status dan catatan. Data dari nasabah dikunci (disabled).
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')->label('Kode tiket')->disabled(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->required(),

                TextInput::make('jenis')->label('Jenis layanan')->disabled(),
                TextInput::make('kategori')->label('Kategori')->disabled(),
                TextInput::make('nama')->label('Nama lengkap')->disabled(),
                TextInput::make('nik')->label('NIK')->disabled(),
                TextInput::make('no_rekening')->label('Nomor rekening / nasabah')->disabled(),
                TextInput::make('telepon')->label('Nomor HP')->disabled(),
                TextInput::make('email')->label('Email')->disabled(),
                TextInput::make('cabang')->label('Cabang / unit layanan')->disabled(),
                DatePicker::make('tanggal_kejadian')->label('Tanggal kejadian')->disabled(),

                Textarea::make('isi')
                    ->label('Uraian')
                    ->rows(8)
                    ->disabled()
                    ->columnSpanFull(),

                Textarea::make('catatan')
                    ->label('Catatan internal')
                    ->helperText('Hanya terlihat oleh admin.')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
