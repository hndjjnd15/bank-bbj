<?php

namespace App\Filament\Resources\PengurusPerusahaans\Schemas;

use App\Models\PengurusPerusahaan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengurusPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Lengkap beserta Gelar')
                    ->placeholder('Contoh: Dr. H. Ahmad Fauzi, S.E., M.M.')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->placeholder('Contoh: Direktur Utama')
                    ->required()
                    ->maxLength(150),

                Select::make('kelompok')
                    ->label('Kelompok')
                    ->options(PengurusPerusahaan::KELOMPOK)
                    ->helperText('Direksi tampil lebih dulu, lalu Dewan Komisaris.')
                    ->required(),

                TextInput::make('urutan')
                    ->label('Urutan tampil')
                    ->helperText('Angka kecil tampil lebih dulu dalam kelompoknya.')
                    ->numeric()
                    ->default(0),

                FileUpload::make('foto')
                    ->label('Foto Profil')
                    ->helperText('Gunakan foto potret (tegak) dengan rasio 3:4. Jika kosong, tampil kotak berisi inisial nama. Maksimal 5 MB.')
                    ->image()
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('pengurus')
                    ->visibility('public'),

                Textarea::make('latar_belakang')
                    ->label('Latar Belakang')
                    ->helperText('Riwayat karier atau pengalaman singkat. Pisahkan paragraf dengan baris baru.')
                    ->rows(6)
                    ->columnSpanFull(),

                Textarea::make('pendidikan')
                    ->label('Pendidikan')
                    ->helperText('Satu baris = satu jenjang. Contoh: S2 Manajemen, Universitas Padjadjaran')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
