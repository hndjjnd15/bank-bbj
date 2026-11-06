<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPelanggaran extends Model
{
    protected $fillable = [
        'kode', 'nama_pelapor', 'email', 'telepon', 'hubungan', 'kategori',
        'tanggal_kejadian', 'lokasi', 'uraian', 'lampiran', 'status', 'catatan',
    ];

    protected $casts = [
        // Identitas pelapor dan isi laporan tersimpan terenkripsi di database
        'nama_pelapor' => 'encrypted',
        'email' => 'encrypted',
        'telepon' => 'encrypted',
        'uraian' => 'encrypted',
        'tanggal_kejadian' => 'date',
        'email_terkirim_at' => 'datetime',
        'wa_terkirim_at' => 'datetime',
    ];

    // Kode unik, contoh: WBS-20261107-K7QX2 (tanpa huruf dan angka yang mirip: I, O, 0, 1)
    public static function buatKode(): string
    {
        $awalan = config('wbs.kode_awalan', 'WBS');
        $tanggal = now()->format('Ymd');
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $acak = '';
            for ($i = 0; $i < 5; $i++) {
                $acak .= $karakter[random_int(0, strlen($karakter) - 1)];
            }
            $kode = "{$awalan}-{$tanggal}-{$acak}";
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    // Alamat halaman tindak lanjut di panel admin
    public function urlAdmin(): string
    {
        try {
            return \App\Filament\Resources\LaporanPelanggarans\LaporanPelanggaranResource::getUrl('edit', ['record' => $this]);
        } catch (\Throwable $e) {
            return url('/admin');
        }
    }
}
