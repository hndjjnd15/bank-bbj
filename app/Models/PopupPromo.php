<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PopupPromo extends Model
{
    protected $fillable = [
        'judul',
        'gambar',
        'alt',
        'tautan',
        'tautan_kustom',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'aktif' => 'boolean',
    ];

    public function scopeTayang(Builder $query): Builder
    {
        return $query
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public function getGambarUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->gambar);
    }

    /** Tautan kustom (jika diisi) menimpa pilihan tautan tujuan. */
    public function getTujuanUrlAttribute(): string
    {
        return filled($this->tautan_kustom) ? $this->tautan_kustom : (string) $this->tautan;
    }

    /**
     * Pilihan tujuan pop-up: halaman penting situs + detail produk yang sudah terbit.
     *
     * @return array<string, string>
     */
    public static function pilihanTautan(): array
    {
        $pilihan = [
            '/pengajuan-kredit' => 'Formulir pengajuan kredit',
            '/tabungan' => 'Halaman produk tabungan',
            '/deposito' => 'Halaman deposito',
            '/kredit' => 'Halaman produk kredit',
            '/lelang' => 'Lelang dan AYDA',
            '/berita' => 'Berita dan kegiatan',
            '/karir' => 'Karir',
            '/faq' => 'FAQ',
            '/pengaduan' => 'Pengaduan nasabah',
            '/wbs' => 'Whistleblowing (WBS)',
            '/ppid' => 'PPID',
        ];

        $produk = ProdukBank::query()
            ->where('diterbitkan', true)
            ->whereIn('jenis', ['tabungan', 'kredit'])
            ->orderBy('jenis')
            ->orderBy('judul')
            ->get();

        foreach ($produk as $item) {
            $rute = $item->jenis === 'kredit' ? 'kredit.show' : 'tabungan.show';

            $pilihan[route($rute, ['slug' => $item->slug], false)] = 'Produk: '.$item->judul;
        }

        return $pilihan;
    }
}
