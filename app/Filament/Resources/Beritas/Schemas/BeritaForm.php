<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BeritaForm
{
    // Formulir tambah/ubah berita di panel admin.
    // Nama kolom (judul, slug, tanggal, ringkasan, isi, foto) = kolom tabel beritas.
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)   // saat kolom ditinggalkan, slug ikut terisi otomatis
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? '')))
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->label('Alamat singkat (slug)')
                    ->helperText('Terisi otomatis dari judul. Jadi alamat halaman: /berita/slug-ini')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // tidak boleh sama dengan berita lain

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),

                Textarea::make('ringkasan')
                    ->label('Deskripsi singkat')
                    ->rows(2)
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('isi')
                    ->label('Isi berita')
                    ->required()
                    ->rows(12)
                    ->columnSpanFull(),

                FileUpload::make('foto')
                    ->label('Foto')
                    ->helperText('Format: semua jenis gambar (JPG, JPEG, PNG, WEBP, GIF, dan lainnya). Maksimal 5 MB per foto, hingga 8 foto. Foto pertama menjadi foto utama; urutan bisa digeser.')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->maxFiles(8)
                    ->maxSize(5120)            // maksimal 5 MB per foto (satuan KB: 5 x 1024)
                    ->disk('public')           // tersimpan di storage/app/public
                    ->directory('berita')      // di dalam folder berita/
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }
}
