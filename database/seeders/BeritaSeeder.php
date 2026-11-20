<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'judul' => 'BPR LPK Parungpanjang Jalin Kerja Sama dengan Bank BJB Cabang Cibinong',
                'slug' => 'kerja-sama-bank-bjb-cabang-cibinong',
                'ringkasan' => 'Bank BBJ dan Bank BJB Cabang Cibinong menandatangani kerja sama pada 8 Juni 2024.',
                'isi' => "Pada 8 Juni 2024, BPR LPK Parungpanjang menandatangani perjanjian kerja sama dengan Bank BJB Cabang Cibinong.\n\nKerja sama ini menjadi bagian dari pengembangan layanan dan kemitraan Bank BBJ.",
                'foto' => null,
                'tanggal' => '2024-06-08',
            ],
            [
                'judul' => 'Kerja Sama Produk DPLK BJB SIAP dengan Bank BJB Kabupaten Tangerang',
                'slug' => 'kerja-sama-dplk-bjb-siap-bank-bjb-tangerang',
                'ringkasan' => 'Kerja sama terkait produk DPLK BJB SIAP ditandatangani pada 14 November 2024.',
                'isi' => "Pada 14 November 2024, Bank BBJ menandatangani kerja sama terkait produk DPLK BJB SIAP dengan Bank BJB Kabupaten Tangerang.\n\nInformasi produk dan layanan dapat dikonfirmasi melalui kantor Bank BBJ.",
                'foto' => null,
                'tanggal' => '2024-11-14',
            ],
            [
                'judul' => 'Pengumuman Perpindahan dan Penutupan KC Pancoran Mas',
                'slug' => 'pengumuman-perpindahan-penutupan-kc-pancoran-mas',
                'ringkasan' => 'Informasi perpindahan dan penutupan kantor cabang Pancoran Mas diumumkan pada 22 Oktober 2024.',
                'isi' => "Bank BBJ menyampaikan pengumuman mengenai perpindahan dan penutupan kantor cabang Pancoran Mas pada 22 Oktober 2024.\n\nNasabah dapat menghubungi kantor Bank BBJ untuk memperoleh informasi layanan dan kantor tujuan.",
                'foto' => null,
                'tanggal' => '2024-10-22',
            ],
        ];

        foreach ($data as $berita) {
            Berita::updateOrCreate(['slug' => $berita['slug']], $berita);
        }
    }
}
