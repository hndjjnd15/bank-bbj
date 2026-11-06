<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTriwulan extends Model
{
    public const PERIODE = [
        3 => 'Maret',
        6 => 'Juni',
        9 => 'September',
        12 => 'Desember',
    ];

    protected $fillable = ['tahun', 'periode', 'berkas'];

    protected $casts = ['berkas' => 'array'];

    public function getNamaPeriodeAttribute(): string
    {
        return self::PERIODE[$this->periode] ?? (string) $this->periode;
    }

    // Daftar berkas yang siap ditampilkan: [['judul' => ..., 'url' => ..., 'ekstensi' => 'PDF'], ...]
    // Baris tanpa file diabaikan.
    public function getBerkasSiapAttribute(): array
    {
        return collect($this->berkas ?? [])
            ->filter(fn ($b) => ! empty($b['file']))
            ->map(fn ($b) => [
                'judul' => $b['judul'] ?? ('Laporan ' . $this->nama_periode . ' ' . $this->tahun),
                'url' => asset('storage/' . $b['file']),
                'ekstensi' => strtoupper(pathinfo($b['file'], PATHINFO_EXTENSION) ?: 'FILE'),
            ])
            ->values()
            ->all();
    }
}
