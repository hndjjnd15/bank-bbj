<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PengaturanSitus extends Model
{
    protected $table = 'pengaturan_situs';

    // Nomor dan pesan awal tombol WhatsApp mengambang; bisa diubah lewat panel admin.
    public const WHATSAPP_BAWAAN = '6287828038885';

    public const PESAN_BAWAAN = 'Halo Bank BBJ, saya ingin bertanya.';

    private const KUNCI_WADAH = 'pengaturan-situs';

    protected $fillable = [
        'whatsapp',
        'whatsapp_pesan',
        'whatsapp_aktif',
        'pengajuan_kredit_samarkan_nik',
    ];

    protected $casts = [
        'whatsapp_aktif' => 'boolean',
        'pengajuan_kredit_samarkan_nik' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Setelah disimpan lewat admin, nilai lama di wadah request dibuang.
        static::saved(fn () => app()->forgetInstance(self::KUNCI_WADAH));
        static::deleted(fn () => app()->forgetInstance(self::KUNCI_WADAH));
    }

    /**
     * Pengaturan situs hanya satu baris. Dibuat otomatis dengan nilai awal
     * supaya halaman publik tidak perlu menangani data kosong.
     */
    public static function saatIni(): ?self
    {
        if (app()->bound(self::KUNCI_WADAH)) {
            return app(self::KUNCI_WADAH);
        }

        // Jangan sampai halaman publik gagal dibuka sebelum migrasi dijalankan.
        if (! Schema::hasTable('pengaturan_situs')) {
            return null;
        }

        $pengaturan = self::query()->firstOrCreate([], [
            'whatsapp' => self::WHATSAPP_BAWAAN,
            'whatsapp_pesan' => self::PESAN_BAWAAN,
            'whatsapp_aktif' => true,
            'pengajuan_kredit_samarkan_nik' => false,
        ]);

        app()->instance(self::KUNCI_WADAH, $pengaturan);

        return $pengaturan;
    }

    // Nomor format internasional tanpa tanda baca: "0812-3456" menjadi "628123456".
    public function getNomorWhatsappAttribute(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->whatsapp);

        if ($nomor === '') {
            return null;
        }

        if (str_starts_with($nomor, '0')) {
            $nomor = '62'.substr($nomor, 1);
        } elseif (! str_starts_with($nomor, '62')) {
            $nomor = '62'.$nomor;
        }

        return $nomor;
    }

    public function getTautanWhatsappAttribute(): ?string
    {
        $nomor = $this->nomor_whatsapp;

        if ($nomor === null) {
            return null;
        }

        $tautan = 'https://wa.me/'.$nomor;

        if (filled($this->whatsapp_pesan)) {
            $tautan .= '?text='.rawurlencode((string) $this->whatsapp_pesan);
        }

        return $tautan;
    }

    // Tombol hanya tampil jika diaktifkan dan nomornya terisi.
    public function getTampilAttribute(): bool
    {
        return (bool) $this->whatsapp_aktif && $this->tautan_whatsapp !== null;
    }
}
