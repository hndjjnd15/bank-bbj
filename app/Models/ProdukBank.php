<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukBank extends Model
{
    protected $table = 'produk_banks';

    public const JENIS = [
        'deposito' => 'Deposito',
        'tabungan' => 'Tabungan',
        'kredit' => 'Kredit',
    ];

    protected $fillable = [
        'jenis', 'judul', 'slug', 'tagline', 'deskripsi', 'deskripsi_rata', 'setoran_minimal',
        'keunggulan', 'persyaratan', 'ketentuan', 'foto_utama', 'flyer', 'brosur_pdf', 'diterbitkan',
    ];

    protected $casts = [
        'flyer' => 'array',
        'diterbitkan' => 'boolean',
        'setoran_minimal' => 'integer',
    ];

    // "berkas/a.jpg" -> unggahan admin; "/images/..." atau "http..." dipakai apa adanya
    private static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return (str_starts_with($path, '/') || str_starts_with($path, 'http')) ? $path : asset('storage/' . $path);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return self::url($this->foto_utama);
    }

    public function getBrosurUrlAttribute(): ?string
    {
        return self::url($this->brosur_pdf);
    }

    public function getFlyerUrlsAttribute(): array
    {
        return collect($this->flyer ?? [])->filter()->map(fn ($p) => self::url($p))->values()->all();
    }

    // Teks berbaris menjadi daftar poin (tanda "-", "*" di awal baris dibuang)
    public static function poin(?string $teks): array
    {
        return collect(preg_split('/\R/', (string) $teks))
            ->map(fn ($baris) => trim(preg_replace('/^\s*[-*\x{2022}]+\s*/u', '', $baris)))
            ->filter()
            ->values()
            ->all();
    }

    public function getKeunggulanPoinAttribute(): array
    {
        return self::poin($this->keunggulan);
    }

    public function getPersyaratanPoinAttribute(): array
    {
        return self::poin($this->persyaratan);
    }

    public function getKetentuanPoinAttribute(): array
    {
        return self::poin($this->ketentuan);
    }

    // Paragraf deskripsi: dipisah baris kosong
    public function getDeskripsiParagrafAttribute(): array
    {
        return collect(preg_split('/\R{2,}/', trim((string) $this->deskripsi)))->filter()->values()->all();
    }
}
