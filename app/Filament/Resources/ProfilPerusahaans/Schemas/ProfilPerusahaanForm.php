<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use App\Models\ProfilPerusahaan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfilPerusahaanForm
{
    // Pilihan perataan teks: kiri, tengah, kanan, atau kanan-kiri (justify)
    private static function perataan(string $nama, string $label): Select
    {
        return Select::make($nama)
            ->label($label)
            ->options(ProfilPerusahaan::RATA)
            ->default('rata')
            ->required()
            ->native(false);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Sejarah dan Foto Gedung')
                    ->schema([
                        Textarea::make('sejarah')
                            ->label('Sejarah Perusahaan')
                            ->helperText('Pisahkan paragraf dengan satu baris kosong.')
                            ->rows(12),

                        self::perataan('sejarah_rata', 'Perataan teks sejarah'),

                        FileUpload::make('foto_gedung')
                            ->label('Foto Gedung')
                            ->helperText('Tampil di sebelah kanan sejarah dengan rasio 3:4 (potret). Gunakan foto tegak. Semua jenis gambar, maksimal 5 MB.')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('profil')
                            ->visibility('public'),
                    ]),

                Section::make('2. Visi dan Misi')
                    ->columns(2)
                    ->schema([
                        Textarea::make('visi')
                            ->label('Visi')
                            ->rows(6),

                        Textarea::make('misi')
                            ->label('Misi')
                            ->helperText('Satu baris = satu poin. Tanda "-" di awal baris tidak perlu diketik.')
                            ->rows(6),

                        self::perataan('visi_rata', 'Perataan teks visi'),
                        self::perataan('misi_rata', 'Perataan teks misi'),
                    ]),

                Section::make('3. Struktur Organisasi')
                    ->schema([
                        FileUpload::make('foto_struktur')
                            ->label('Gambar Struktur Organisasi')
                            ->helperText('Di website bisa diklik untuk diperbesar (pop-up). Gunakan gambar yang cukup besar agar tulisannya terbaca. Maksimal 10 MB.')
                            ->image()
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('profil')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
