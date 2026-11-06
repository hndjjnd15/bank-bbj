<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPpid extends Model
{
    protected $fillable = ['judul', 'kategori', 'tahun', 'tanggal_terbit', 'deskripsi', 'file', 'diterbitkan'];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'diterbitkan' => 'boolean',
    ];

    public function getKategoriLabelAttribute(): string
    {
        return config('ppid.kategori')[$this->kategori] ?? $this->kategori;
    }

    // Alamat unduh (file disimpan di storage publik)
    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }

    // Jenis file untuk lencana, contoh: PDF
    public function getEkstensiAttribute(): string
    {
        return strtoupper(pathinfo((string) $this->file, PATHINFO_EXTENSION) ?: 'FILE');
    }
}
