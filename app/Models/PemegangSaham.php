<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemegangSaham extends Model
{
    protected $table = 'pemegang_sahams';

    protected $fillable = ['nama', 'modal_dasar', 'modal_disetor', 'urutan'];

    // "Rp 36.720.000.000"
    public static function rupiah($nilai): string
    {
        return 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    }

    // "51.00%" (dua angka desimal, titik sebagai pemisah, sesuai tulisan di website lama)
    public static function persen(float $bagian, float $total): string
    {
        return ($total > 0 ? number_format($bagian / $total * 100, 2, '.', '') : '0.00') . '%';
    }
}
