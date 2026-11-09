<?php

namespace App\Filament\Resources\ProdukBanks\Schemas;

use App\Models\ProdukBank;
use App\Support\Perataan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class ProdukBankForm
{
    // Teks pratinjau, contoh: "Preview: Rp 5.000.000"
    private static function preview($state): string
    {
        $angka = preg_replace('/\D+/', '', (string) $state);

        return $angka !== '' ? 'Preview: Rp ' . number_format((float) $angka, 0, ',', '.') : 'Ketik angka saja, titik pemisah ribuan muncul otomatis.';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Identitas Produk')
                    ->columns(2)
                    ->schema([
                        Select::make('jenis')
                            ->label('Jenis Produk')
                            ->options(ProdukBank::JENIS)
                            ->default('deposito')
                            ->required(),

                        Toggle::make('diterbitkan')
                            ->label('Tampilkan di website')
                            ->helperText('Matikan untuk menyembunyikan halaman produk tanpa menghapusnya.')
                            ->default(true),

                        TextInput::make('judul')
                            ->label('Nama Produk')
                            ->placeholder('Contoh: Deposito')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)   // saat kolom ditinggalkan, slug terisi otomatis
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Alamat singkat (slug)')
                            ->helperText('Terisi otomatis dari nama produk. Halaman deposito di website selalu di alamat /deposito.')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('setoran_minimal')
                            ->label('Setoran Minimal (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->stripCharacters('.')
                            ->live(onBlur: true)
                            ->helperText(fn ($state) => self::preview($state)),

                        TextInput::make('tagline')
                            ->label('Kalimat Singkat di Bawah Judul')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('2. Deskripsi dan Keunggulan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->helperText('Pisahkan paragraf dengan satu baris kosong.')
                            ->rows(8),

                        Select::make('deskripsi_rata')
                            ->label('Perataan teks deskripsi')
                            ->options(Perataan::OPSI)
                            ->default('rata')
                            ->required()
                            ->native(false),

                        Textarea::make('keunggulan')
                            ->label('Keunggulan')
                            ->helperText('Satu baris = satu keunggulan. Tanda "-" di awal baris tidak perlu diketik.')
                            ->rows(6),
                    ]),

                Section::make('3. Persyaratan dan Ketentuan')
                    ->columns(2)
                    ->schema([
                        Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->helperText('Satu baris = satu poin.')
                            ->rows(7),

                        Textarea::make('ketentuan')
                            ->label('Ketentuan')
                            ->helperText('Satu baris = satu poin.')
                            ->rows(7),
                    ]),

                Section::make('4. Foto, Flyer, dan Brosur')
                    ->schema([
                        FileUpload::make('foto_utama')
                            ->label('Foto Produk')
                            ->helperText('Tampil di sebelah kanan pengantar produk (rasio 4:3). Semua jenis gambar, maksimal 5 MB.')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('produk')
                            ->visibility('public'),

                        FileUpload::make('flyer')
                            ->label('Gambar Flyer')
                            ->helperText('Tampil utuh dan bisa diklik untuk pop-up di website. Boleh lebih dari satu (misalnya depan dan belakang), maksimal 4. Maksimal 5 MB per gambar.')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(4)
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('produk')
                            ->visibility('public'),

                        FileUpload::make('brosur_pdf')
                            ->label('Berkas Brosur (PDF)')
                            ->helperText('Untuk tombol "Unduh Brosur". Format PDF, maksimal 20 MB. Berkas bersifat publik.')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->disk('public')
                            ->directory('produk')
                            ->visibility('public')
                            ->downloadable(),
                    ]),
            ]);
    }
}
