<?php

namespace Database\Seeders;

use App\Models\LaporanTahunan;
use App\Models\LaporanTriwulan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([2022, 2023, 2024, 2025] as $tahun) {
            foreach ([3, 6, 9, 12] as $periode) {
                LaporanTriwulan::updateOrCreate(
                    ['tahun' => $tahun, 'periode' => $periode],
                    ['berkas' => []],
                );
            }
        }

        foreach ([3, 6, 9] as $periode) {
            LaporanTriwulan::updateOrCreate(
                ['tahun' => 2026, 'periode' => $periode],
                ['berkas' => []],
            );
        }

        foreach ([2024, 2025, 2026] as $tahun) {
            LaporanTahunan::query()->firstOrCreate(['tahun' => $tahun]);
        }
    }
}
