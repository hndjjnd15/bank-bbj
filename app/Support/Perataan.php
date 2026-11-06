<?php

namespace App\Support;

// Pilihan perataan teks yang bisa dipilih admin
class Perataan
{
    public const OPSI = [
        'kiri' => 'Rata kiri',
        'tengah' => 'Rata tengah',
        'kanan' => 'Rata kanan',
        'rata' => 'Rata kanan-kiri (justify)',
    ];

    // Kelas CSS, contoh: "rata-rata" (justify). Nilai tidak dikenal dianggap justify.
    public static function kelas(?string $nilai): string
    {
        return 'rata-' . (array_key_exists($nilai ?? '', self::OPSI) ? $nilai : 'rata');
    }
}
