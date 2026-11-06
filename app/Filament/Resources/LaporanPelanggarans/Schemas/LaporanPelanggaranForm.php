<?php

namespace App\Filament\Resources\LaporanPelanggarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LaporanPelanggaranForm
{
    // Tim hanya mengubah status dan catatan. Data dari pelapor dikunci (disabled).
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tindak lanjut')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')->label('Kode laporan')->disabled(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'baru' => 'Baru',
                                'diverifikasi' => 'Diverifikasi',
                                'ditindaklanjuti' => 'Ditindaklanjuti',
                                'selesai' => 'Selesai',
                            ])
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan internal')
                            ->helperText('Hanya terlihat oleh tim yang memiliki akses panel admin.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Identitas pelapor (rahasia)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_pelapor')->label('Nama pelapor')->placeholder('(tidak dicantumkan)')->disabled(),
                        TextInput::make('email')->label('Email')->disabled(),
                        TextInput::make('telepon')->label('Nomor telepon')->placeholder('-')->disabled(),
                        TextInput::make('hubungan')->label('Hubungan dengan bank')->disabled(),
                    ]),

                Section::make('Isi laporan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kategori')->label('Kategori')->disabled(),
                        DatePicker::make('tanggal_kejadian')->label('Tanggal kejadian')->disabled(),
                        TextInput::make('lokasi')->label('Lokasi / unit kerja')->placeholder('-')->disabled()->columnSpanFull(),
                        Textarea::make('uraian')->label('Uraian laporan')->rows(10)->disabled()->columnSpanFull(),
                    ]),
            ]);
    }
}
