<?php

namespace App\Filament\Resources\PengajuanKredits\Schemas;

use App\Models\PengajuanKredit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class PengajuanKreditForm
{
    // Admin hanya mengubah status dan catatan. Data dari pemohon dikunci (disabled).
    public static function configure(Schema $schema): Schema
    {
        // Kolom identitas utama sudah ditampilkan di bagian atas, jadi tidak diulang
        $lewati = [
            'nama_lengkap', 'nik', 'email', 'jenis_kredit', 'no_hp', 'nominal_kredit',
            'domisili', 'status_pernikahan', 'alamat_domisili',
        ];

        $tambahan = [];

        foreach (config('pengajuan-kredit.label_data', []) as $kunci => $judul) {
            if (in_array($kunci, $lewati, true)) {
                continue;
            }

            $field = (str_contains($kunci, 'alamat') || str_contains($kunci, 'lokasi'))
                ? Textarea::make('data.'.$kunci)->rows(2)
                : TextInput::make('data.'.$kunci);

            $field->label($judul)
                ->disabled()
                ->visible(fn (Get $get): bool => filled($get('data.'.$kunci)));

            if (str_starts_with($kunci, 'tanggal_')) {
                $field->formatStateUsing(function ($state) {
                    try {
                        return filled($state) ? Carbon::parse($state)->format('d-m-Y') : null;
                    } catch (\Throwable $e) {
                        return $state;
                    }
                });
            }

            $tambahan[] = $field;
        }

        return $schema
            ->columns(2)
            ->components([
                Section::make('Pemohon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode')->label('Nomor tiket')->disabled(),
                        TextInput::make('nama_produk')->label('Produk kredit')->disabled(),
                        TextInput::make('nama_lengkap')->label('Nama lengkap')->disabled(),
                        TextInput::make('nik')->label('Nomor KTP/NIK')->disabled(),
                        TextInput::make('email')->label('Email')->disabled(),
                        TextInput::make('no_hp')->label('Nomor HP/WA')->disabled(),
                        TextInput::make('nominal_kredit')
                            ->label('Nominal pengajuan')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Select::make('jenis_kredit')
                            ->label('Jenis produk kredit')
                            ->options(config('pengajuan-kredit.produk'))
                            ->disabled(),
                        TextInput::make('domisili')
                            ->label('Domisili')
                            ->formatStateUsing(fn ($state) => config('pengajuan-kredit.domisili.'.$state, $state))
                            ->disabled(),
                        TextInput::make('status_pernikahan')
                            ->label('Status pernikahan')
                            ->formatStateUsing(fn ($state) => config('pengajuan-kredit.status_pernikahan.'.$state, $state))
                            ->disabled(),
                        Textarea::make('alamat_domisili')
                            ->label('Alamat domisili')
                            ->rows(2)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Pengajuan')
                    ->columns(2)
                    ->schema($tambahan),

                Section::make('Dokumen')
                    ->schema([
                        View::make('filament.pengajuan-kredit.berkas')
                            ->viewData(fn (?PengajuanKredit $record): array => [
                                'pengajuan' => $record,
                                'berkas' => $record?->berkasTampil() ?? [],
                            ]),
                    ]),

                Section::make('Tindak lanjut')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(PengajuanKredit::STATUS)
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan internal')
                            ->helperText('Hanya terlihat oleh admin.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
