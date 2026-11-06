<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    // Pilihan di formulir admin (ubah di sini jika perlu)
    public const TIPE_ASET = [
        'Rumah Tinggal', 'Tanah Kosong', 'Ruko / Rukan', 'Gudang', 'Apartemen', 'Kendaraan', 'Lainnya',
    ];

    public const SERTIFIKAT = [
        'SHM (Sertifikat Hak Milik)',
        'SHGB (Sertifikat Hak Guna Bangunan)',
        'SHMSRS (Hak Milik Satuan Rumah Susun)',
        'AJB / Girik',
        'BPKB',
        'Lainnya',
    ];

    public const STATUS = [
        'lelang' => 'Akan Dilelang',
        'ayda' => 'Dijual (AYDA)',
        'terjual' => 'Terjual',
    ];

    protected $fillable = [
        'judul', 'slug', 'tipe_aset', 'sertifikat', 'status', 'jadwal_lelang',
        'luas_tanah', 'luas_bangunan',
        'alamat', 'kota', 'koordinat', 'link_maps',
        'harga_limit', 'harga_pasar',
        'pic_nama', 'pic_whatsapp', 'link_instagram',
        'deskripsi', 'foto_utama', 'galeri',
    ];

    protected $casts = [
        'jadwal_lelang' => 'datetime',
        'galeri' => 'array',
        'luas_tanah' => 'float',
        'luas_bangunan' => 'float',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    // ---------- Foto: foto utama selalu pertama, lalu galeri ----------
    public function getFotoUrlsAttribute(): array
    {
        return collect(array_merge([$this->foto_utama], $this->galeri ?? []))
            ->filter()
            ->unique()
            ->map(fn ($p) => (str_starts_with($p, '/') || str_starts_with($p, 'http')) ? $p : asset('storage/' . $p))
            ->values()
            ->all();
    }

    // ---------- Harga & luas ----------
    public function getHargaLimitRpAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_limit, 0, ',', '.');
    }

    public function getHargaPasarRpAttribute(): ?string
    {
        return $this->harga_pasar ? 'Rp ' . number_format($this->harga_pasar, 0, ',', '.') : null;
    }

    public static function formatLuas($nilai): string
    {
        if ($nilai === null || $nilai === '') {
            return '-';
        }

        return rtrim(rtrim(number_format((float) $nilai, 2, ',', '.'), '0'), ',') . ' m²';
    }

    // Perkiraan harga limit per m² tanah, contoh: "Rp 7.083.333"
    public function getHargaPerM2RpAttribute(): ?string
    {
        if (! $this->luas_tanah || $this->luas_tanah <= 0) {
            return null;
        }

        return 'Rp ' . number_format($this->harga_limit / $this->luas_tanah, 0, ',', '.');
    }

    // Persentase harga limit di bawah estimasi harga pasar, contoh: "10,5". Null jika tidak lebih rendah.
    public function getSelisihPasarPersenAttribute(): ?string
    {
        if (! $this->harga_pasar || $this->harga_pasar <= $this->harga_limit) {
            return null;
        }

        return number_format(($this->harga_pasar - $this->harga_limit) / $this->harga_pasar * 100, 1, ',', '.');
    }

    // ---------- WhatsApp: membuka chat dengan pesan tanya jaminan yang sudah terisi ----------
    public function getWaUrlAttribute(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->pic_whatsapp);
        if ($nomor === '') {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);   // 0812... -> 62812...
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        $pesan = 'Halo ' . ($this->pic_nama ?: 'Tim Lelang') . ', saya tertarik dengan jaminan "'
            . $this->judul . '" (' . $this->kota . '). Mohon informasi lebih lanjut.';

        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode($pesan);
    }

    // ---------- Peta ----------
    // Mengembalikan [latitude, longitude] atau null jika format koordinat salah
    public function getKoordinatArrayAttribute(): ?array
    {
        if (! preg_match('/^\s*(-?\d{1,2}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)\s*$/', (string) $this->koordinat, $m)) {
            return null;
        }
        [$lat, $lng] = [(float) $m[1], (float) $m[2]];

        return (abs($lat) <= 90 && abs($lng) <= 180) ? [$lat, $lng] : null;
    }

    // Alamat untuk iframe peta interaktif.
    // Prioritas: link kustom berbentuk embed Google Maps, lalu koordinat GPS, lalu alamat.
    public function getPetaEmbedUrlAttribute(): ?string
    {
        if ($this->link_maps) {
            $host = parse_url($this->link_maps, PHP_URL_HOST);
            $path = (string) parse_url($this->link_maps, PHP_URL_PATH);
            if (in_array($host, ['www.google.com', 'google.com', 'maps.google.com'], true)
                && str_starts_with($path, '/maps/embed')) {
                return $this->link_maps;
            }
        }

        if ($k = $this->koordinat_array) {
            return 'https://www.google.com/maps?q=' . $k[0] . ',' . $k[1] . '&z=16&output=embed';
        }

        $alamat = trim($this->alamat . ' ' . $this->kota);

        return $alamat !== '' ? 'https://www.google.com/maps?q=' . rawurlencode($alamat) . '&z=15&output=embed' : null;
    }

    // Tujuan tombol "Buka di Google Maps"
    public function getPetaBukaUrlAttribute(): ?string
    {
        if ($this->link_maps) {
            return $this->link_maps;
        }
        if ($k = $this->koordinat_array) {
            return 'https://www.google.com/maps/search/?api=1&query=' . $k[0] . ',' . $k[1];
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(trim($this->alamat . ' ' . $this->kota));
    }
}
