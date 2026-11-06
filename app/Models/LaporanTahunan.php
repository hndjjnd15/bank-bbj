<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTahunan extends Model
{
    // Kolom file => judul kolom di tabel website (urutan = urutan kolom)
    public const KOLOM = [
        'file_laporan_tahunan' => 'Laporan Tahunan',
        'file_tata_kelola' => 'Laporan Tata Kelola',
        'file_keuangan_berkelanjutan' => 'Laporan Keuangan Berkelanjutan',
        'file_ppid' => 'Laporan PPID',
    ];

    protected $fillable = ['tahun', 'file_laporan_tahunan', 'file_tata_kelola', 'file_keuangan_berkelanjutan', 'file_ppid'];

    // Alamat unduh untuk satu kolom, atau null jika kosong
    public function urlFile(string $kolom): ?string
    {
        $path = $this->{$kolom} ?? null;

        return $path ? asset('storage/' . $path) : null;
    }
}
