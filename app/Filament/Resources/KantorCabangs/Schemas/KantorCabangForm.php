<?php

namespace App\Filament\Resources\KantorCabangs\Schemas;

use App\Models\KantorCabang;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KantorCabangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Identitas Kantor')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Kantor')
                            ->placeholder('Contoh: Cabang Parungpanjang')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)   // saat kolom ditinggalkan, slug terisi otomatis
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Alamat singkat (slug)')
                            ->helperText('Terisi otomatis dari nama kantor. Alamat halaman: /jaringan-kantor/slug-ini')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('tipe')
                            ->label('Jenis Kantor')
                            ->options(array_combine(KantorCabang::TIPE, KantorCabang::TIPE))
                            ->default('Kantor Cabang')
                            ->required(),

                        TextInput::make('urutan')
                            ->label('Urutan tampil')
                            ->helperText('Angka kecil tampil lebih dulu dalam jenis yang sama.')
                            ->numeric()
                            ->default(0),

                        Toggle::make('diterbitkan')
                            ->label('Tampilkan di website')
                            ->helperText('Matikan untuk menyembunyikan kantor tanpa menghapusnya.')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('2. Alamat dan Peta')
                    ->columns(2)
                    ->schema([
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('kota')
                            ->label('Kota / Kabupaten / Kecamatan')
                            ->helperText('Dipakai untuk penyaring wilayah di website.')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('koordinat')
                            ->label('Titik Koordinat GPS')
                            ->placeholder('-6.3694, 106.5603')
                            ->helperText('Format: Latitude, Longitude. Dipakai langsung untuk peta Google Maps interaktif. Jika kosong, peta memakai alamat.')
                            ->regex('/^\s*-?\d{1,2}(\.\d+)?\s*,\s*-?\d{1,3}(\.\d+)?\s*$/')
                            ->validationMessages(['regex' => 'Format harus: latitude, longitude (contoh: -6.3694, 106.5603).']),

                        TextInput::make('link_maps')
                            ->label('Link Google Maps Kustom (Opsional)')
                            ->helperText('Kosongkan bila ingin otomatis memakai koordinat atau alamat. Tombol "Buka di Google Maps" memakai link ini. Jika berupa link "embed" Google Maps, peta di halaman memakainya.')
                            ->url()
                            ->regex('/^https?:\/\//i')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('3. Kontak dan Jam Operasional')
                    ->columns(2)
                    ->schema([
                        TextInput::make('telepon')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(30)
                            ->placeholder('(0251) 123456'),

                        TextInput::make('whatsapp')
                            ->label('WhatsApp (Opsional)')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('081234567890')
                            ->helperText('Boleh diawali 0 atau 62. Dipakai untuk tombol "Chat via WhatsApp".'),

                        TextInput::make('email')
                            ->label('Email (Opsional)')
                            ->email()
                            ->maxLength(150)
                            ->columnSpanFull(),

                        Textarea::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->helperText('Satu baris = satu jadwal. Contoh: Senin - Jumat: 08.00 - 16.00 WIB')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('4. Foto Kantor')
                    ->schema([
                        FileUpload::make('foto_utama')
                            ->label('Foto Utama')
                            ->helperText('Foto tampak depan kantor. Semua jenis gambar, maksimal 5 MB.')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('kantor')
                            ->visibility('public'),

                        FileUpload::make('galeri')
                            ->label('Galeri Foto (Opsional)')
                            ->helperText('Boleh lebih dari satu (maksimal 8). Maksimal 5 MB per foto. Urutan bisa digeser.')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(8)
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('kantor')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
