<?php

namespace Database\Seeders;

use App\Models\LaporanTahunan;
use App\Models\LaporanTriwulan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        // Data CONTOH tanpa berkas, hanya untuk uji tampilan. Unggah berkas asli lewat /admin.
        foreach ([2026, 2025] as $tahun) {
            foreach ([3, 6, 9, 12] as $periode) {
                LaporanTriwulan::updateOrCreate(['tahun' => $tahun, 'periode' => $periode], ['berkas' => []]);
            }
        }

        foreach ([2024, 2025, 2026] as $tahun) {
            LaporanTahunan::updateOrCreate(['tahun' => $tahun], []);
        }
    }
}
