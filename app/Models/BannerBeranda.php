<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BannerBeranda extends Model
{
    protected $fillable = [
        'judul',
        'teks',
        'alt',
        'gambar_desktop',
        'gambar_mobile',
        'durasi_detik',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'durasi_detik' => 'integer',
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

    public function getGambarDesktopUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->gambar_desktop);
    }

    public function getGambarMobileUrlAttribute(): string
    {
        return filled($this->gambar_mobile)
            ? Storage::disk('public')->url($this->gambar_mobile)
            : $this->gambar_desktop_url;
    }
}
