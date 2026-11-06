<?php

namespace App\Filament\Resources\PermohonanInformasis\Schemas;

use App\Models\PermohonanInformasi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermohonanInformasiForm
{
    // Petugas hanya mengubah status dan catatan. Data dari pemohon dikunci (disabled).
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tindak lanjut')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')->label('Nomor registrasi')->disabled(),

                        Select::make('status')
                            ->label('Status')
                            ->options(PermohonanInformasi::STATUS)
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan internal')
                            ->helperText('Hanya terlihat oleh petugas yang memiliki akses panel admin. Contoh: tanggal tanggapan tertulis dikirim.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Data pemohon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('jenis_pemohon')->label('Jenis pemohon')->disabled(),
                        TextInput::make('nama')->label('Nama')->disabled(),
                        TextInput::make('nomor_identitas')->label('Nomor identitas (KTP / NPWP)')->disabled(),
                        TextInput::make('pekerjaan')->label('Pekerjaan')->placeholder('-')->disabled(),
                        TextInput::make('telepon')->label('Nomor HP')->disabled(),
                        TextInput::make('email')->label('Email')->disabled(),
                        Textarea::make('alamat')->label('Alamat')->rows(2)->disabled()->columnSpanFull(),
                    ]),

                Section::make('Isi permohonan')
                    ->schema([
                        Textarea::make('rincian')->label('Informasi yang dibutuhkan')->rows(6)->disabled(),
                        Textarea::make('tujuan')->label('Tujuan penggunaan')->rows(3)->disabled(),
                        TextInput::make('cara_memperoleh')->label('Cara memperoleh informasi')->disabled(),
                    ]),
            ]);
    }
}
