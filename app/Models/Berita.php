<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = ['judul', 'slug', 'ringkasan', 'isi', 'foto', 'tanggal'];

    protected $casts = [
        'tanggal' => 'date',
        'foto' => 'array',   // kolom JSON dibaca sebagai array PHP
    ];

    // Daftar alamat foto yang siap dipakai di <img>.
    // - "berita/a.jpg"  -> foto unggahan admin (storage/app/public/berita/a.jpg)
    // - "/images/..." atau "http..." -> dipakai apa adanya
    public function getFotoUrlsAttribute(): array
    {
        return collect($this->foto ?? [])
            ->map(function ($p) {
                return (str_starts_with($p, '/') || str_starts_with($p, 'http'))
                    ? $p
                    : asset('storage/' . $p);
            })
            ->values()
            ->all();
    }
}
