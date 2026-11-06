<?php

namespace App\Filament\Resources\Lowongans\Schemas;

use App\Models\Lowongan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LowonganForm
{
    // Daftar pilihan: label sama dengan nilainya
    private static function pilihan(array $daftar): array
    {
        return array_combine($daftar, $daftar);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. Informasi Lowongan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->label('Nama Posisi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)   // saat kolom ditinggalkan, slug terisi otomatis
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Alamat singkat (slug)')
                            ->helperText('Terisi otomatis dari nama posisi. Alamat halaman: /karir/slug-ini. Jika ada dua lowongan dengan nama posisi sama, bedakan slug-nya (misalnya tambahkan nama cabang).')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        TextInput::make('divisi')
                            ->label('Divisi / Bagian')
                            ->maxLength(100)
                            ->placeholder('Contoh: Operasional'),

                        TextInput::make('penempatan')
                            ->label('Penempatan')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Cabang Parungpanjang')
                            ->helperText('Cabang atau kantor tempat bekerja.'),

                        Select::make('tipe_kerja')
                            ->label('Tipe Pekerjaan')
                            ->options(self::pilihan(Lowongan::TIPE_KERJA))
                            ->required(),

                        Select::make('pendidikan_minimal')
                            ->label('Pendidikan Minimal')
                            ->options(self::pilihan(Lowongan::PENDIDIKAN)),

                        TextInput::make('pengalaman')
                            ->label('Pengalaman')
                            ->maxLength(150)
                            ->placeholder('Contoh: Fresh graduate / minimal 1 tahun'),

                        DatePicker::make('batas_lamaran')
                            ->label('Batas Akhir Lamaran')
                            ->helperText('Boleh dikosongkan. Setelah tanggal ini lowongan otomatis tampil "Ditutup".'),

                        Toggle::make('dibuka')
                            ->label('Lowongan dibuka')
                            ->helperText('Matikan untuk menutup lowongan lebih awal.')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('2. Deskripsi Pekerjaan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Tugas dan Tanggung Jawab')
                            ->required()
                            ->rows(8),
                    ]),

                Section::make('3. Persyaratan')
                    ->schema([
                        Textarea::make('persyaratan')
                            ->label('Persyaratan Pelamar')
                            ->helperText('Satu baris = satu poin. Tanda "-" di awal baris tidak perlu diketik.')
                            ->required()
                            ->rows(8),
                    ]),

                Section::make('4. Benefit / Fasilitas (Opsional)')
                    ->schema([
                        Textarea::make('benefit')
                            ->label('Yang Didapat Karyawan')
                            ->helperText('Satu baris = satu poin. Boleh dikosongkan.')
                            ->rows(5),
                    ]),

                Section::make('5. Cara Melamar')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email_lamaran')
                            ->label('Email HRD Penerima Lamaran')
                            ->email()
                            ->required()
                            ->maxLength(150),

                        TextInput::make('wa_lamaran')
                            ->label('WhatsApp HRD (Opsional)')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('081234567890')
                            ->helperText('Boleh diawali 0 atau 62. Dipakai untuk tombol "Tanya via WhatsApp".'),

                        Textarea::make('catatan_lamaran')
                            ->label('Catatan untuk Pelamar (Opsional)')
                            ->helperText('Contoh: Lampirkan CV, pas foto, KTP, dan ijazah terakhir.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
