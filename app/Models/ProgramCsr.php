<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCsr extends Model
{
    // Pilihan kategori di formulir admin (ubah di sini jika perlu)
    public const KATEGORI = [
        'Pendidikan',
        'Kesehatan',
        'Lingkungan',
        'Sosial dan Kemanusiaan',
        'Ekonomi dan UMKM',
        'Keagamaan',
        'Lainnya',
    ];

    protected $fillable = [
        'judul', 'slug', 'kategori', 'tanggal', 'lokasi', 'penerima_manfaat', 'mitra',
        'ringkasan', 'isi', 'isi_rata', 'foto_utama', 'galeri', 'diterbitkan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'galeri' => 'array',
        'diterbitkan' => 'boolean',
    ];

    // Foto utama selalu pertama, lalu galeri. Alamat siap dipakai di <img>.
    public function getFotoUrlsAttribute(): array
    {
        return collect(array_merge([$this->foto_utama], $this->galeri ?? []))
            ->filter()
            ->unique()
            ->map(fn ($p) => (str_starts_with($p, '/') || str_starts_with($p, 'http')) ? $p : asset('storage/' . $p))
            ->values()
            ->all();
    }

    // Paragraf deskripsi: dipisah baris kosong
    public function getIsiParagrafAttribute(): array
    {
        return collect(preg_split('/\R{2,}/', trim((string) $this->isi)))->filter()->values()->all();
    }
}
