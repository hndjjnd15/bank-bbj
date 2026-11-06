<?php

namespace App\Filament\Resources\DokumenPpids\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DokumenPpidForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->label('Judul Dokumen')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Select::make('kategori')
                    ->label('Kategori Informasi')
                    ->options(config('ppid.kategori'))
                    ->required(),

                TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->required()
                    ->minValue(1990)
                    ->maxValue(2100)
                    ->default((int) now()->format('Y')),

                DatePicker::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->helperText('Boleh dikosongkan.'),

                Toggle::make('diterbitkan')
                    ->label('Tampilkan di website')
                    ->helperText('Matikan untuk menyembunyikan dokumen tanpa menghapusnya.')
                    ->default(true),

                Textarea::make('deskripsi')
                    ->label('Keterangan Singkat')
                    ->helperText('Boleh dikosongkan.')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('file')
                    ->label('Berkas Dokumen')
                    ->helperText('Format: PDF, Word, atau Excel. Maksimal 20 MB. Berkas bersifat publik dan dapat diunduh siapa saja.')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->maxSize(20480)
                    ->disk('public')
                    ->directory('ppid')
                    ->visibility('public')
                    ->downloadable()
                    ->columnSpanFull(),
            ]);
    }
}
