<?php

namespace App\Filament\Resources\LaporanTriwulans\Schemas;

use App\Models\LaporanTriwulan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaporanTriwulanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->required()
                    ->minValue(1990)
                    ->maxValue(2100)
                    ->default((int) now()->format('Y')),

                Select::make('periode')
                    ->label('Periode (bulan akhir triwulan)')
                    ->options(LaporanTriwulan::PERIODE)
                    ->required()
                    // Satu data per tahun dan periode
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn ($rule, $get) => $rule->where('tahun', $get('tahun')))
                    ->validationMessages(['unique' => 'Periode ini untuk tahun tersebut sudah ada. Ubah data yang sudah ada, jangan buat baru.']),

                Repeater::make('berkas')
                    ->label('Berkas Laporan')
                    ->helperText('Boleh lebih dari satu berkas per periode (misalnya laporan publikasi dan laporan lainnya). Berkas tampil saat periode dibuka di website.')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Nama Berkas')
                            ->placeholder('Contoh: Laporan Keuangan Publikasi Triwulan III')
                            ->required()
                            ->maxLength(150),

                        FileUpload::make('file')
                            ->label('File')
                            ->helperText('Format: PDF, Word, atau Excel. Maksimal 20 MB. Berkas bersifat publik.')
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
                            ->downloadable()
                            ->required(),
                    ])
                    ->addActionLabel('Tambah berkas')
                    ->defaultItems(1)
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
