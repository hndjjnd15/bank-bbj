<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanInformasi extends Model
{
    protected $table = 'permohonan_informasis';

    public const STATUS = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'dipenuhi' => 'Dipenuhi',
        'ditolak' => 'Ditolak',
    ];

    protected $fillable = [
        'kode', 'jenis_pemohon', 'nama', 'nomor_identitas', 'alamat', 'pekerjaan', 'telepon', 'email',
        'rincian', 'tujuan', 'cara_memperoleh', 'status', 'catatan',
    ];

    protected $casts = [
        'nomor_identitas' => 'encrypted',   // tersimpan terenkripsi di database
        'alamat' => 'encrypted',
        'email_terkirim_at' => 'datetime',
        'wa_terkirim_at' => 'datetime',
    ];

    // Nomor registrasi unik, contoh: PPID-20261108-K7QX2 (tanpa I, O, 0, 1 agar mudah dibaca)
    public static function buatKode(): string
    {
        $awalan = config('ppid.kode_awalan', 'PPID');
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

    // Perkiraan batas tanggapan tertulis: 10 hari kerja sejak diterima.
    // Hanya menghitung Senin-Jumat; hari libur nasional belum diperhitungkan.
    public function getBatasTanggapanAttribute()
    {
        return $this->created_at->copy()->addWeekdays((int) config('ppid.tenggat_hari_kerja', 10))->endOfDay();
    }

    // Belum dituntaskan dan sudah melewati perkiraan batas tanggapan
    public function getTerlambatAttribute(): bool
    {
        return in_array($this->status, ['baru', 'diproses'], true) && now()->greaterThan($this->batas_tanggapan);
    }

    // Alamat halaman tindak lanjut di panel admin
    public function urlAdmin(): string
    {
        try {
            return \App\Filament\Resources\PermohonanInformasis\PermohonanInformasiResource::getUrl('edit', ['record' => $this]);
        } catch (\Throwable $e) {
            return url('/admin');
        }
    }
}
