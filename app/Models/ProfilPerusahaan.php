<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $table = 'profil_perusahaans';

    // Pilihan perataan teks di panel admin => kelas CSS di website
    public const RATA = [
        'kiri' => 'Rata kiri',
        'tengah' => 'Rata tengah',
        'kanan' => 'Rata kanan',
        'rata' => 'Rata kanan-kiri (justify)',
    ];

    protected $fillable = [
        'sejarah', 'sejarah_rata', 'foto_gedung',
        'visi', 'visi_rata', 'misi', 'misi_rata',
        'foto_struktur',
    ];

    // Kelas CSS untuk perataan, contoh: "rata-rata" (justify). Nilai tidak dikenal dianggap justify.
    public static function kelasRata(?string $nilai): string
    {
        return 'rata-' . (array_key_exists($nilai ?? '', self::RATA) ? $nilai : 'rata');
    }

    // Paragraf sejarah: dipisah baris kosong
    public function getSejarahParagrafAttribute(): array
    {
        return collect(preg_split('/\R{2,}/', trim((string) $this->sejarah)))->filter()->values()->all();
    }

    // "berkas/a.jpg" -> foto unggahan admin; "/images/..." atau "http..." dipakai apa adanya
    private static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return (str_starts_with($path, '/') || str_starts_with($path, 'http')) ? $path : asset('storage/' . $path);
    }

    public function getFotoGedungUrlAttribute(): ?string
    {
        return self::url($this->foto_gedung);
    }

    public function getFotoStrukturUrlAttribute(): ?string
    {
        return self::url($this->foto_struktur);
    }

    // Misi sebagai daftar poin (satu baris = satu poin; tanda "-" atau "*" di awal dibuang)
    public function getMisiPoinAttribute(): array
    {
        return collect(preg_split('/\R/', (string) $this->misi))
            ->map(fn ($baris) => trim(preg_replace('/^\s*[-*\x{2022}]+\s*/u', '', $baris)))
            ->filter()
            ->values()
            ->all();
    }
}
