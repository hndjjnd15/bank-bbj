<?php

namespace App\Filament\Resources\PopupPromos\Schemas;

use App\Models\PopupPromo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PopupPromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('judul')
                    ->label('Nama pop-up')
                    ->helperText('Hanya untuk catatan admin, tidak tampil di situs.')
                    ->required()
                    ->maxLength(160)
                    ->columnSpanFull(),

                FileUpload::make('gambar')
                    ->label('Gambar pop-up')
                    ->helperText(
                        'Rasio rekomendasi 16:9 — misalnya 1600 × 900 px atau 1920 × 1080 px. '.
                        'Format JPG, PNG, atau WEBP, maksimal 5 MB. '.
                        'Ukuran pop-up mengikuti rasio gambar; gambar 4:5 atau 1:1 akan tampil lebih tinggi di ponsel.'
                    )
                    ->image()
                    ->imagePreviewHeight('240')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('popup-promo')
                    ->visibility('public')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('alt')
                    ->label('Teks alternatif gambar')
                    ->helperText('Jelaskan isi gambar untuk pembaca layar, misalnya “Promo tabungan dan kredit Bank BBJ”.')
                    ->required()
                    ->maxLength(255),

                TextInput::make('urutan')
                    ->label('Urutan tampil')
                    ->helperText('Pop-up aktif dengan urutan terkecil yang tampil di beranda.')
                    ->numeric()
                    ->rule('integer')
                    ->minValue(0)
                    ->default(fn (): int => ((int) PopupPromo::query()->max('urutan')) + 1)
                    ->required(),

                Select::make('tautan')
                    ->label('Tautan tujuan')
                    ->helperText('Halaman yang dibuka saat gambar pop-up diklik. Detail produk muncul otomatis jika produknya sudah terbit.')
                    ->options(fn (): array => PopupPromo::pilihanTautan())
                    ->default('/pengajuan-kredit')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('tautan_kustom')
                    ->label('Tautan khusus (opsional)')
                    ->helperText('Isi hanya jika tujuan belum ada di daftar, misalnya /kredit atau https://contoh.com. Jika diisi, tautan ini yang dipakai.')
                    ->maxLength(2048)
                    ->rules(['nullable', 'string', 'max:2048', 'regex:#^(/|https?://)#i'])
                    ->validationMessages([
                        'regex' => 'Tautan harus diawali “/” (halaman internal) atau “http(s)://”.',
                    ])
                    ->columnSpanFull(),

                Toggle::make('aktif')
                    ->label('Tampilkan pop-up di beranda')
                    ->helperText('Jika dimatikan, pop-up tidak muncul. Jika semua pop-up dimatikan, tidak ada pop-up yang tampil.')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }
}
