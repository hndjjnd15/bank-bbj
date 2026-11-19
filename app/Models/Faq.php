<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    public const HALAMAN = [
        'beranda' => 'Beranda',
        'deposito' => 'Produk Deposito',
        'tabungan' => 'Produk Tabungan',
        'kredit' => 'Produk Kredit',
    ];

    protected $fillable = [
        'halaman',
        'produk_bank_id',
        'tanya',
        'jawab',
        'urutan',
        'diterbitkan',
    ];

    protected $casts = [
        'produk_bank_id' => 'integer',
        'urutan' => 'integer',
        'diterbitkan' => 'boolean',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(ProdukBank::class, 'produk_bank_id');
    }

    public function scopeTerbit(Builder $query): Builder
    {
        return $query
            ->where('diterbitkan', true)
            ->orderBy('urutan')
            ->orderBy('id');
    }
}
