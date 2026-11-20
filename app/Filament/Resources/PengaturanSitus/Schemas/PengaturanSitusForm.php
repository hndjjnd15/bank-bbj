<?php

namespace App\Filament\Resources\PengaturanSitus\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PengaturanSitusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Tombol WhatsApp Mengambang')
                    ->description('Tombol bulat di pojok kanan bawah website. Nomor yang sama juga dipakai pada tautan WhatsApp di strip informasi atas.')
                    ->columns(1)
                    ->schema([
                        Toggle::make('whatsapp_aktif')
                            ->label('Tampilkan tombol WhatsApp')
                            ->default(true)
                            ->columnSpanFull(),

                        TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(32)
                            ->placeholder('Contoh: 081234567890')
                            ->helperText('Boleh diawali 08, +62, atau 62. Spasi dan tanda hubung diabaikan.')
                            ->required(fn (Get $get): bool => (bool) $get('whatsapp_aktif'))
                            ->columnSpanFull(),

                        Textarea::make('whatsapp_pesan')
                            ->label('Pesan otomatis')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Sudah terisi di aplikasi WhatsApp saat pengunjung menekan tombol. Kosongkan bila tidak perlu.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Animasi Loading Halaman')
                    ->description('Animasi GIF yang menutupi layar selama halaman sedang dimuat, misalnya saat pengunjung membuka halaman lain atau mengirim formulir.')
                    ->columns(1)
                    ->schema([
                        Toggle::make('loading_aktif')
                            ->label('Tampilkan animasi loading')
                            ->helperText('Jika dimatikan, animasi tidak muncul sama sekali di website.')
                            ->default(false)
                            ->columnSpanFull(),

                        FileUpload::make('loading_gif')
                            ->label('File animasi (.gif)')
                            ->helperText(
                                'Format GIF animasi berulang. Disarankan 200 × 200 px dengan latar transparan, '.
                                'animasi singkat, dan ukuran maksimal 2 MB agar halaman tetap cepat dibuka.'
                            )
                            ->image()
                            ->imagePreviewHeight('120')
                            ->acceptedFileTypes(['image/gif'])
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('loading')
                            ->visibility('public')
                            ->required(fn (Get $get): bool => (bool) $get('loading_aktif'))
                            ->columnSpanFull(),

                        TextInput::make('loading_durasi')
                            ->label('Durasi tampil (detik)')
                            ->helperText('Lama animasi loading ditampilkan sebelum halaman dibuka. Isi 1–15 detik; bawaan 2 detik.')
                            ->integer()
                            ->minValue(1)
                            ->maxValue(15)
                            ->default(2)
                            ->suffix('detik')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Privasi Email Pengajuan Kredit')
                    ->description('Atur tampilan NIK pada email internal yang dikirim ke tim kredit.')
                    ->schema([
                        Toggle::make('pengajuan_kredit_samarkan_nik')
                            ->label('Samarkan NIK pada email pengajuan kredit')
                            ->helperText('Jika aktif, NIK pada email internal disamarkan dan hanya empat digit terakhir yang ditampilkan. Jika nonaktif, NIK ditampilkan lengkap.')
                            ->default(false),
                    ]),
            ]);
    }
}
