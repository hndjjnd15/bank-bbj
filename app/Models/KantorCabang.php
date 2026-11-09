<?php

namespace App\Models;

use App\Models\Concerns\MemilikiPeta;
use Illuminate\Database\Eloquent\Model;

class KantorCabang extends Model
{
    use MemilikiPeta;

    // Urutan tampil: Kantor Pusat lebih dulu, lalu cabang, lalu kas
    public const TIPE = ['Kantor Pusat', 'Kantor Cabang', 'Kantor Kas'];

    protected $fillable = [
        'nama', 'slug', 'tipe', 'alamat', 'kota', 'telepon', 'whatsapp', 'email',
        'jam_operasional', 'koordinat', 'link_maps', 'foto_utama', 'galeri', 'urutan', 'diterbitkan',
    ];

    protected $casts = [
        'galeri' => 'array',
        'diterbitkan' => 'boolean',
    ];

    // Foto utama selalu pertama, lalu galeri. Alamat siap dipakai di <img>.
    public function getFotoUrlsAttribute(): array
    {
        return collect(array_merge([$this->foto_utama], $this->galeri ?? []))
            ->filter()
            ->unique()
            ->map(fn ($p) => (str_starts_with($p, '/') || str_starts_with($p, 'http')) ? $p : asset('storage/' . $p))
            ->values()
            ->all();
    }

    // Jam operasional sebagai daftar baris
    public function getJamBarisAttribute(): array
    {
        return collect(preg_split('/\R/', (string) $this->jam_operasional))
            ->map(fn ($b) => trim($b))
            ->filter()
            ->values()
            ->all();
    }

    // Nomor telepon untuk tautan tel:, contoh: "+62254430000"
    public function getTeleponLinkAttribute(): ?string
    {
        $nomor = preg_replace('/[^0-9+]/', '', (string) $this->telepon);

        return $nomor !== '' ? $nomor : null;
    }

    // Chat WhatsApp dengan pesan yang sudah terisi
    public function getWaUrlAttribute(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->whatsapp);
        if ($nomor === '') {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode('Halo, saya ingin bertanya mengenai layanan di ' . $this->nama . '.');
    }
}
