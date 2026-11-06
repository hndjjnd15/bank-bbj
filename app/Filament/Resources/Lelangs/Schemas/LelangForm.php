<?php

namespace App\Filament\Resources\Lelangs\Schemas;

use App\Models\Lelang;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class LelangForm
{
    // Daftar pilihan: label sama dengan nilainya
    private static function pilihan(array $daftar): array
    {
        return array_combine($daftar, $daftar);
    }

    // Teks pratinjau harga, contoh: "Preview: Rp 850.000.000"
    private static function previewRupiah($state): string
    {
        $angka = preg_replace('/\D+/', '', (string) $state);   // buang titik dan karakter non-angka

        return $angka !== ''
            ? 'Preview: Rp ' . number_format((float) $angka, 0, ',', '.')
            : 'Ketik angka saja, titik pemisah ribuan muncul otomatis.';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. Identitas Jaminan & Status Lelang')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->label('Nama Jaminan / Judul Properti Agunan')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)   // saat kolom ditinggalkan, slug terisi otomatis
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Alamat singkat (slug)')
                            ->helperText('Terisi otomatis dari judul. Alamat halaman: /lelang/slug-ini')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('tipe_aset')
                            ->label('Tipe Aset Agunan')
                            ->options(self::pilihan(Lelang::TIPE_ASET))
                            ->required(),

                        Select::make('sertifikat')
                            ->label('Sertifikat Legalitas')
                            ->options(self::pilihan(Lelang::SERTIFIKAT))
                            ->required(),

                        Select::make('status')
                            ->label('Status Penawaran Lelang')
                            ->options(Lelang::STATUS)
                            ->default('lelang')
                            ->required(),

                        DateTimePicker::make('jadwal_lelang')
                            ->label('Tanggal / Jadwal Lelang')
                            ->helperText('Boleh dikosongkan.')
                            ->seconds(false),
                    ]),

                Section::make('2. Spesifikasi Fisik (Luas Tanah & Bangunan)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('luas_tanah')
                            ->label('Luas Tanah')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('m²'),

                        TextInput::make('luas_bangunan')
                            ->label('Luas Bangunan')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('m²'),
                    ]),

                Section::make('3. Lokasi, Titik Koordinat GPS & Google Maps')
                    ->columns(2)
                    ->schema([
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap Jaminan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('kota')
                            ->label('Kota / Kabupaten')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('koordinat')
                            ->label('Titik Koordinat GPS')
                            ->placeholder('-6.3694, 106.5603')
                            ->helperText('Format: Latitude, Longitude. Dipakai langsung untuk peta Google Maps interaktif.')
                            ->regex('/^\s*-?\d{1,2}(\.\d+)?\s*,\s*-?\d{1,3}(\.\d+)?\s*$/')
                            ->validationMessages(['regex' => 'Format harus: latitude, longitude (contoh: -6.3694, 106.5603).']),

                        TextInput::make('link_maps')
                            ->label('Link Google Maps Kustom (Opsional)')
                            ->helperText('Kosongkan bila ingin otomatis memakai koordinat GPS atau alamat di atas. Tombol "Buka di Google Maps" memakai link ini. Jika berupa link "embed" Google Maps, peta di halaman memakainya.')
                            ->url()
                            ->regex('/^https?:\/\//i')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('4. Nilai Agunan & Harga Limit Lelang')
                    ->columns(2)
                    ->schema([
                        TextInput::make('harga_limit')
                            ->label('Harga Limit Lelang (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))   // 850.000.000 saat mengetik
                            ->stripCharacters('.')                                    // yang disimpan: 850000000
                            ->live(onBlur: true)
                            ->helperText(fn ($state) => self::previewRupiah($state)),

                        TextInput::make('harga_pasar')
                            ->label('Estimasi Harga Pasar (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))   // 850.000.000 saat mengetik
                            ->stripCharacters('.')                                    // yang disimpan: 850000000
                            ->live(onBlur: true)
                            ->helperText(fn ($state) => self::previewRupiah($state)),
                    ]),

                Section::make('5. Kontak WA Marketing & Link Sosmed Instagram')
                    ->columns(2)
                    ->schema([
                        TextInput::make('pic_nama')
                            ->label('Nama PIC / Marketing Lelang')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('pic_whatsapp')
                            ->label('Nomor Kontak WhatsApp Marketing')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('081234567890')
                            ->helperText('Otomatis membuka chat WhatsApp dengan pesan tanya jaminan yang sudah terisi. Boleh diawali 0 atau 62.'),

                        TextInput::make('link_instagram')
                            ->label('Link Sosmed Instagram ke Postingan Jaminan')
                            ->placeholder('https://www.instagram.com/p/...')
                            ->url()
                            ->regex('/^https?:\/\/(www\.)?instagram\.com\/.+/i')
                            ->validationMessages(['regex' => 'Link harus berasal dari instagram.com.'])
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('6. Deskripsi Lengkap Jaminan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Keterangan & Kondisi Fisik Jaminan')
                            ->required()
                            ->rows(10),
                    ]),

                Section::make('7. Foto Utama & Galeri Foto-Foto Jaminan')
                    ->schema([
                        FileUpload::make('foto_utama')
                            ->label('Upload Foto Utama')
                            ->helperText('Format: semua jenis gambar. Maksimal 5 MB.')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('lelang')
                            ->visibility('public'),

                        FileUpload::make('galeri')
                            ->label('Galeri Foto-Foto Jaminan')
                            ->helperText('Boleh lebih dari satu (maksimal 12). Maksimal 5 MB per foto. Urutan bisa digeser.')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(12)
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('lelang')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
