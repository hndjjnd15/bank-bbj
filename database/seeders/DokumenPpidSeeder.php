<?php

namespace Database\Seeders;

use App\Models\DokumenPpid;
use Illuminate\Database\Seeder;

class DokumenPpidSeeder extends Seeder
{
    public function run(): void
    {
        // Data CONTOH untuk uji tampilan (belum ada berkas). Hapus lewat /admin sebelum website tayang.
        $data = [
            ['judul' => 'Laporan Keuangan Tahunan 2025', 'kategori' => 'berkala', 'tahun' => 2025, 'deskripsi' => 'Laporan keuangan tahunan yang telah diaudit.'],
            ['judul' => 'Laporan Keuangan Publikasi Triwulan III 2026', 'kategori' => 'berkala', 'tahun' => 2026, 'deskripsi' => 'Laporan publikasi triwulanan.'],
            ['judul' => 'Profil dan Struktur Organisasi', 'kategori' => 'berkala', 'tahun' => 2026, 'deskripsi' => 'Profil perusahaan, dewan komisaris, dan direksi.'],
            ['judul' => 'Laporan Tahunan 2025', 'kategori' => 'berkala', 'tahun' => 2025, 'deskripsi' => null],
            ['judul' => 'Pengumuman Gangguan Layanan', 'kategori' => 'serta_merta', 'tahun' => 2026, 'deskripsi' => 'Contoh informasi yang perlu diumumkan seketika.'],
            ['judul' => 'Daftar Informasi Publik', 'kategori' => 'setiap_saat', 'tahun' => 2026, 'deskripsi' => 'Daftar seluruh informasi publik yang dikuasai.'],
            ['judul' => 'Prosedur Pelayanan Informasi Publik', 'kategori' => 'setiap_saat', 'tahun' => 2026, 'deskripsi' => 'Tata cara permohonan, keberatan, dan penyelesaian sengketa informasi.'],
        ];

        foreach ($data as $d) {
            DokumenPpid::updateOrCreate(['judul' => $d['judul']], $d + ['diterbitkan' => true]);
        }
    }
}
