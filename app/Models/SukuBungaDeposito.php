<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SukuBungaDeposito extends Model
{
    protected $table = 'suku_bunga_depositos';

    protected $fillable = ['tenor_bulan', 'nominal_min', 'nominal_maks', 'bunga'];

    protected $casts = ['bunga' => 'float'];

    // Nominal ringkas: 5000000 -> "5 juta", 1500000000 -> "1,5 miliar"
    public static function ringkas(int $n): string
    {
        $format = fn (float $v) => rtrim(rtrim(number_format($v, 2, ',', '.'), '0'), ',');

        if ($n >= 1_000_000_000) {
            return $format($n / 1_000_000_000) . ' miliar';
        }
        if ($n >= 1_000_000) {
            return $format($n / 1_000_000) . ' juta';
        }

        return number_format($n, 0, ',', '.');
    }

    // Label rentang nominal, contoh: "Rp 5 juta - < Rp 100 juta" atau "≥ Rp 500 juta"
    public function getLabelAttribute(): string
    {
        if ($this->nominal_maks === null) {
            return '≥ Rp ' . self::ringkas((int) $this->nominal_min);
        }

        return 'Rp ' . self::ringkas((int) $this->nominal_min) . ' - < Rp ' . self::ringkas((int) $this->nominal_maks);
    }

    // Kunci unik rentang nominal (untuk mengelompokkan kolom tabel)
    public function getKunciRentangAttribute(): string
    {
        return $this->nominal_min . '-' . ($this->nominal_maks ?? 'tak-terbatas');
    }

    // Persen berformat Indonesia, contoh: "5,25%"
    public static function persen(float $nilai): string
    {
        return number_format($nilai, 2, ',', '.') . '%';
    }
}
