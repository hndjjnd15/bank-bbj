<?php

namespace App\Models\Concerns;

// Logika peta Google Maps. Model harus punya kolom: koordinat, link_maps, alamat, kota.
trait MemilikiPeta
{
    // [latitude, longitude] atau null jika format koordinat salah
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
            if (in_array($host, ['www.google.com', 'google.com', 'maps.google.com'], true) && str_starts_with($path, '/maps/embed')) {
                return $this->link_maps;
            }
        }

        if ($k = $this->koordinat_array) {
            return 'https://www.google.com/maps?q=' . $k[0] . ',' . $k[1] . '&z=17&output=embed';
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

    // Tujuan tombol "Petunjuk arah" (rute dari lokasi pengunjung)
    public function getPetunjukArahUrlAttribute(): string
    {
        $tujuan = ($k = $this->koordinat_array) ? $k[0] . ',' . $k[1] : rawurlencode(trim($this->alamat . ' ' . $this->kota));

        return 'https://www.google.com/maps/dir/?api=1&destination=' . $tujuan;
    }
}
