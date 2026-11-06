<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengurusPerusahaan extends Model
{
    protected $table = 'pengurus_perusahaans';

    // kunci => judul kelompok di website (urutan = urutan tampil: Direksi lebih dulu)
    public const KELOMPOK = [
        'direksi' => 'Direksi',
        'komisaris' => 'Dewan Komisaris',
    ];

    protected $fillable = ['nama', 'jabatan', 'kelompok', 'foto', 'urutan', 'latar_belakang', 'pendidikan'];

    // Pendidikan sebagai daftar (satu baris = satu jenjang; tanda "-" di awal dibuang)
    public function getPendidikanPoinAttribute(): array
    {
        return collect(preg_split('/\R/', (string) $this->pendidikan))
            ->map(fn ($baris) => trim(preg_replace('/^\s*[-*\x{2022}]+\s*/u', '', $baris)))
            ->filter()
            ->values()
            ->all();
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (blank($this->foto)) {
            return null;
        }

        return (str_starts_with($this->foto, '/') || str_starts_with($this->foto, 'http')) ? $this->foto : asset('storage/' . $this->foto);
    }

    // Huruf awal untuk avatar jika belum ada foto, contoh: "AB"
    public function getInisialAttribute(): string
    {
        return collect(preg_split('/\s+/', trim($this->nama)))
            ->filter()
            ->take(2)
            ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
            ->implode('');
    }
}
