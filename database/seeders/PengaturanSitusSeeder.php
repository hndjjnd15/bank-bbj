<?php

namespace Database\Seeders;

use App\Models\PengaturanSitus;
use Illuminate\Database\Seeder;

class PengaturanSitusSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanSitus::query()->firstOrCreate([], [
            'whatsapp' => '6281110020707',
            'whatsapp_pesan' => 'Halo Bank BBJ, saya ingin bertanya.',
            'whatsapp_aktif' => true,
            'loading_aktif' => false,
            'loading_gif' => null,
            'loading_durasi' => 2,
            'pengajuan_kredit_samarkan_nik' => false,
        ]);
    }
}
