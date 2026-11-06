<?php

namespace App\Filament\Resources\ProgramCsrs\Schemas;

use App\Models\ProgramCsr;
use App\Support\Perataan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProgramCsrForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Informasi Program')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->label('Nama Program / Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)   // saat kolom ditinggalkan, slug terisi otomatis
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Alamat singkat (slug)')
                            ->helperText('Terisi otomatis dari nama program. Alamat halaman: /csr/slug-ini')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('kategori')
                            ->label('Kategori')
                            ->options(array_combine(ProgramCsr::KATEGORI, ProgramCsr::KATEGORI))
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Pelaksanaan')
                            ->required()
                            ->default(now()),

                        TextInput::make('lokasi')
                            ->label('Lokasi')
                            ->placeholder('Contoh: Kecamatan Parungpanjang, Kabupaten Bogor')
                            ->maxLength(150),

                        TextInput::make('penerima_manfaat')
                            ->label('Penerima Manfaat')
                            ->placeholder('Contoh: 150 siswa SD')
                            ->maxLength(150),

                        TextInput::make('mitra')
                            ->label('Mitra / Pihak Terkait (Opsional)')
                            ->placeholder('Contoh: bersama Dinas Pendidikan')
                            ->maxLength(150),

                        Toggle::make('diterbitkan')
                            ->label('Tampilkan di website')
                            ->helperText('Matikan untuk menyembunyikan program tanpa menghapusnya.')
                            ->default(true),
                    ]),

                Section::make('2. Deskripsi')
                    ->schema([
                        Textarea::make('ringkasan')
                            ->label('Ringkasan Singkat')
                            ->helperText('Satu atau dua kalimat. Tampil di kartu daftar program.')
                            ->rows(2)
                            ->maxLength(255),

                        Textarea::make('isi')
                            ->label('Uraian Kegiatan')
                            ->helperText('Pisahkan paragraf dengan satu baris kosong.')
                            ->required()
                            ->rows(12),

                        Select::make('isi_rata')
                            ->label('Perataan teks uraian')
                            ->options(Perataan::OPSI)
                            ->default('rata')
                            ->required()
                            ->native(false),
                    ]),

                Section::make('3. Foto Kegiatan')
                    ->schema([
                        FileUpload::make('foto_utama')
                            ->label('Foto Utama')
                            ->helperText('Format: semua jenis gambar. Maksimal 5 MB.')
                            ->image()
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('csr')
                            ->visibility('public'),

                        FileUpload::make('galeri')
                            ->label('Galeri Foto Kegiatan')
                            ->helperText('Boleh lebih dari satu (maksimal 12). Maksimal 5 MB per foto. Urutan bisa digeser.')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(12)
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('csr')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
