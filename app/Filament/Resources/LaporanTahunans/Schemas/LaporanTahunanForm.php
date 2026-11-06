<?php

namespace App\Filament\Resources\LaporanTahunans\Schemas;

use App\Models\LaporanTahunan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LaporanTahunanForm
{
    public static function configure(Schema $schema): Schema
    {
        // Satu kolom unggah untuk setiap jenis laporan (judulnya diambil dari LaporanTahunan::KOLOM)
        $unggahan = [];
        foreach (LaporanTahunan::KOLOM as $kolom => $label) {
            $unggahan[] = FileUpload::make($kolom)
                ->label($label)
                ->helperText('Kosongkan jika tidak ada. Di website tampil "Tidak Ada Data".')
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])
                ->maxSize(20480)
                ->disk('public')
                ->directory('laporan')
                ->visibility('public')
                ->downloadable();
        }

        return $schema
            ->components([
                TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->required()
                    ->minValue(1990)
                    ->maxValue(2100)
                    ->default((int) now()->format('Y'))
                    ->unique(ignoreRecord: true)
                    ->validationMessages(['unique' => 'Data untuk tahun ini sudah ada. Ubah data yang sudah ada, jangan buat baru.']),

                Section::make('Berkas laporan')
                    ->description('Format: PDF, Word, atau Excel. Maksimal 20 MB per berkas. Berkas bersifat publik dan dapat diunduh siapa saja.')
                    ->columns(2)
                    ->schema($unggahan),
            ]);
    }
}
