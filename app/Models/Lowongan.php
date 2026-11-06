<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    // Pilihan di formulir admin (ubah di sini jika perlu)
    public const TIPE_KERJA = ['Karyawan Tetap', 'Kontrak', 'Magang', 'Paruh Waktu'];

    public const PENDIDIKAN = ['SMA / SMK', 'D3', 'S1', 'S2'];

    protected $fillable = [
        'judul', 'slug', 'divisi', 'penempatan', 'tipe_kerja', 'pendidikan_minimal',
        'pengalaman', 'batas_lamaran', 'dibuka',
        'deskripsi', 'persyaratan', 'benefit',
        'email_lamaran', 'wa_lamaran', 'catatan_lamaran',
    ];

    protected $casts = [
        'batas_lamaran' => 'date',
        'dibuka' => 'boolean',
    ];

    // Ditutup jika dimatikan admin, atau batas lamaran sudah lewat (sampai akhir hari batasnya)
    public function getSudahDitutupAttribute(): bool
    {
        if (! $this->dibuka) {
            return true;
        }

        return $this->batas_lamaran !== null && $this->batas_lamaran->copy()->endOfDay()->isPast();
    }

    // Sisa hari sampai batas lamaran (0 = hari ini). Null jika tanpa batas atau sudah ditutup.
    public function getSisaHariAttribute(): ?int
    {
        if ($this->batas_lamaran === null || $this->sudah_ditutup) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->batas_lamaran->copy()->startOfDay(), false);
    }

    // Ubah teks berbaris menjadi daftar poin. Tanda awal seperti "-", "*", atau "•" dibuang.
    public static function poin(?string $teks): array
    {
        return collect(preg_split('/\R/', (string) $teks))
            ->map(fn ($baris) => trim(preg_replace('/^\s*[-*\x{2022}]+\s*/u', '', $baris)))
            ->filter()
            ->values()
            ->all();
    }

    // Membuka aplikasi email dengan subjek dan isi yang sudah terisi
    public function getMailtoUrlAttribute(): string
    {
        $subjek = 'Lamaran ' . $this->judul . ($this->penempatan ? ' - ' . $this->penempatan : '');
        $isi = "Yth. Tim HRD,\n\nSaya bermaksud melamar posisi {$this->judul}. Berikut saya lampirkan berkas lamaran saya.\n\nTerima kasih.";

        return 'mailto:' . $this->email_lamaran . '?subject=' . rawurlencode($subjek) . '&body=' . rawurlencode($isi);
    }

    // Membuka chat WhatsApp HRD dengan pesan yang sudah terisi
    public function getWaUrlAttribute(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->wa_lamaran);
        if ($nomor === '') {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode('Halo, saya ingin melamar posisi "' . $this->judul . '" (' . $this->penempatan . '). Mohon informasi lebih lanjut.');
    }
}
