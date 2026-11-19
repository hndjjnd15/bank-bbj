<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SukuBungaDeposito extends Model
{
    protected $table = 'suku_bunga_depositos';

    protected $fillable = ['produk_bank_id', 'tenor_bulan', 'bunga'];

    protected $casts = ['bunga' => 'float'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(ProdukBank::class, 'produk_bank_id');
    }

    // Persen berformat Indonesia, contoh: "5,25%"
    public static function persen(float $nilai): string
    {
        return number_format($nilai, 2, ',', '.').'%';
    }
}
