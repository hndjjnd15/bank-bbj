<?php

namespace App\Filament\Resources\ProdukBanks\Schemas;

use App\Models\ProdukBank;
use App\Support\Perataan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class ProdukBankForm
{
    // Teks pratinjau, contoh: "Preview: Rp 5.000.000"
    private static function preview($state): string
    {
        $angka = preg_replace('/\D+/', '', (string) $state);

        return $angka !== '' ? 'Preview: Rp '.number_format((float) $angka, 0, ',', '.') : 'Ketik angka saja, titik pemisah ribuan muncul otomatis.';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('1. Identitas Produk')
                    ->columns(1)
                    ->schema([
                        Select::make('jenis')
                            ->label('Jenis Produk')
                            ->options(ProdukBank::JENIS)
                            ->default('deposito')
                            ->live()
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
                            ->helperText('Terisi otomatis dari nama produk. Produk kredit menggunakan alamat /kredit/{slug}.')
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
                            ->helperText(fn ($state) => self::preview($state))
                            ->visible(fn (Get $get): bool => $get('jenis') !== 'kredit'),

                        TextInput::make('tagline')
                            ->label('Kalimat Singkat di Bawah Judul')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan Produk Kredit')
                    ->description('Atur kontak WhatsApp dan simulasi cicilan. Kalkulator hanya tampil jika diaktifkan.')
                    ->columns(1)
                    ->visible(fn (Get $get): bool => $get('jenis') === 'kredit')
                    ->schema([
                        TextInput::make('kontak_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(32)
                            ->helperText('Boleh diawali 08 atau 62. Tautan WhatsApp ditampilkan di halaman produk.')
                            ->columnSpanFull(),

                        Toggle::make('simulasi_aktif')
                            ->label('Tampilkan kalkulator kredit')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('suku_bunga_kredit')
                            ->label('Suku bunga per tahun')
                            ->numeric()
                            ->suffix('% p.a.')
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(fn (Get $get): bool => $get('jenis') === 'kredit' && (bool) $get('simulasi_aktif'))
                            ->helperText('Dipakai untuk simulasi cicilan metode anuitas.')
                            ->visible(fn (Get $get): bool => (bool) $get('simulasi_aktif')),

                        TextInput::make('biaya_admin_persen')
                            ->label('Biaya administrasi')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->required(fn (Get $get): bool => $get('jenis') === 'kredit' && (bool) $get('simulasi_aktif'))
                            ->helperText('Persentase biaya dihitung dari jumlah pinjaman.')
                            ->visible(fn (Get $get): bool => (bool) $get('simulasi_aktif')),
                    ]),

                Section::make('Pengaturan Simulasi Tabungan Berjangka')
                    ->description('Kalkulator memakai tenor dan suku bunga TAKAMUM yang diatur pada JavaScript website.')
                    ->visible(fn (Get $get): bool => $get('jenis') === 'tabungan')
                    ->schema([
                        Toggle::make('simulasi_aktif')
                            ->label('Tampilkan kalkulator tabungan berjangka')
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                    ]),

                Section::make(fn (Get $get): string => $get('jenis') === 'kredit' ? '2. Deskripsi Produk' : '2. Deskripsi dan Keunggulan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->helperText('Pisahkan paragraf dengan satu baris kosong.')
                            ->rows(8)
                            ->visible(fn (Get $get): bool => $get('jenis') !== 'kredit')
                            ->dehydrated(fn (Get $get): bool => $get('jenis') !== 'kredit'),

                        RichEditor::make('deskripsi_kredit')
                            ->label('Deskripsi Produk')
                            ->helperText('Atur tebal, ukuran melalui heading/teks kecil, serta daftar poin.')
                            ->afterStateHydrated(fn (RichEditor $component, ?ProdukBank $record) => $component->state(
                                $record?->jenis === 'kredit' ? $record->deskripsi : null
                            ))
                            ->toolbarButtons([
                                ['bold', 'italic'],
                                ['h2', 'h3', 'h4', 'small'],
                                ['bulletList', 'orderedList'],
                                ['undo', 'redo'],
                            ])
                            ->visible(fn (Get $get): bool => $get('jenis') === 'kredit')
                            ->dehydrated(fn (Get $get): bool => $get('jenis') === 'kredit')
                            ->columnSpanFull(),

                        Select::make('deskripsi_rata')
                            ->label('Perataan teks deskripsi')
                            ->options(Perataan::OPSI)
                            ->default('rata')
                            ->required()
                            ->native(false),

                        Textarea::make('keunggulan')
                            ->label(fn (Get $get): string => $get('jenis') === 'tabungan' ? 'Persyaratan Khusus Produk' : 'Keunggulan')
                            ->helperText(fn (Get $get): string => $get('jenis') === 'tabungan'
                                ? 'Satu baris = satu persyaratan khusus produk.'
                                : 'Satu baris = satu keunggulan. Tanda "-" di awal baris tidak perlu diketik.')
                            ->rows(6)
                            ->visible(fn (Get $get): bool => $get('jenis') !== 'kredit'),
                    ]),

                Section::make('3. Persyaratan dan Ketentuan')
                    ->columns(1)
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
                            ->helperText(fn (Get $get): string => $get('jenis') === 'kredit'
                                ? 'Tampil pada kartu produk kredit. Format JPG, JPEG, PNG, atau WEBP, maksimal 5 MB.'
                                : 'Tampil di sebelah kanan pengantar produk (rasio 4:3). Format JPG, JPEG, PNG, atau WEBP, maksimal 5 MB.')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('produk')
                            ->visibility('public'),

                        FileUpload::make('flyer')
                            ->label('Gambar Flyer')
                            ->helperText('Format JPG, JPEG, PNG, atau WEBP. Tampil utuh dan bisa diklik untuk pop-up. Maksimal 4 gambar, 5 MB per gambar.')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
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
