<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'kode', 'jenis', 'nama', 'nik', 'no_rekening', 'telepon', 'email',
        'cabang', 'tanggal_kejadian', 'kategori', 'isi', 'lampiran',
        'status', 'catatan',
    ];

    protected $casts = [
        'nik' => 'encrypted',            // tersimpan terenkripsi di database
        'no_rekening' => 'encrypted',
        'tanggal_kejadian' => 'date',
        'email_terkirim_at' => 'datetime',
        'wa_terkirim_at' => 'datetime',
    ];

    // Membuat kode tiket unik, contoh: BBJ-20261104-K7QX2
    // Kode acak 5 karakter memakai huruf dan angka yang mudah dibaca (tanpa I, O, 0, 1).
    public static function buatKode(): string
    {
        $awalan = config('pengaduan.kode_awalan', 'BBJ');
        $tanggal = now()->format('Ymd');
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $acak = '';
            for ($i = 0; $i < 5; $i++) {
                $acak .= $karakter[random_int(0, strlen($karakter) - 1)];
            }
            $kode = "{$awalan}-{$tanggal}-{$acak}";
        } while (static::where('kode', $kode)->exists()); // ulangi jika kebetulan sudah dipakai

        return $kode;
    }

    // Tampilkan hanya 4 digit terakhir, contoh: ************3456
    public static function samarkan(?string $nilai): string
    {
        if (blank($nilai)) {
            return '-';
        }

        return str_repeat('*', max(strlen($nilai) - 4, 0)) . substr($nilai, -4);
    }

    // Alamat halaman tindak lanjut di panel admin
    public function urlAdmin(): string
    {
        try {
            return \App\Filament\Resources\Pengaduans\PengaduanResource::getUrl('edit', ['record' => $this]);
        } catch (\Throwable $e) {
            return url('/admin');
        }
    }
}
