<?php

namespace Database\Seeders;

use App\Models\PemegangSaham;
use App\Models\PengurusPerusahaan;
use App\Models\ProfilPerusahaan;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {
        // Teks dan gambar di bawah hanya CONTOH. Ganti lewat /admin sebelum website tayang.
        if (! ProfilPerusahaan::query()->exists()) {
            ProfilPerusahaan::create([
                'sejarah' => "Teks contoh. Tuliskan sejarah singkat perusahaan di panel admin.\n\nPisahkan paragraf dengan satu baris kosong.",
                'foto_gedung' => '/images/contoh/foto-1.svg',
                'visi' => 'Teks contoh visi perusahaan. Isi lewat panel admin.',
                'misi' => "Teks contoh misi pertama\nTeks contoh misi kedua\nTeks contoh misi ketiga",
                'foto_struktur' => '/images/contoh/struktur.svg',
            ]);
        }

        // Struktur permodalan (data dari tabel yang diberikan). Persentase dihitung otomatis.
        $saham = [
            ['nama' => 'Pemda Provinsi Jawa Barat', 'modal_dasar' => 36720000000, 'modal_disetor' => 7000000000, 'urutan' => 1],
            ['nama' => 'Pemda Kabupaten Bogor', 'modal_dasar' => 20880000000, 'modal_disetor' => 9998500000, 'urutan' => 2],
            ['nama' => 'PT Bank BJB', 'modal_dasar' => 14400000000, 'modal_disetor' => 1590000000, 'urutan' => 3],
        ];
        foreach ($saham as $s) {
            PemegangSaham::updateOrCreate(['nama' => $s['nama']], $s);
        }

        // Pengurus CONTOH (nama, jabatan, dan riwayat adalah placeholder). Ganti lewat /admin.
        $latar = "Teks contoh. Tuliskan riwayat karier dan pengalaman singkat pengurus di panel admin.";
        $pendidikan = "S2 Manajemen, Universitas Contoh\nS1 Ekonomi, Universitas Contoh";

        $pengurus = [
            ['jabatan' => 'Direktur Utama', 'kelompok' => 'direksi', 'nama' => 'Nama Direktur Utama, S.E., M.M.', 'urutan' => 1],
            ['jabatan' => 'Direktur', 'kelompok' => 'direksi', 'nama' => 'Nama Direktur, S.E., M.M.', 'urutan' => 2],
            ['jabatan' => 'Komisaris Utama', 'kelompok' => 'komisaris', 'nama' => 'Nama Komisaris Utama, S.H., M.H.', 'urutan' => 1],
            ['jabatan' => 'Komisaris', 'kelompok' => 'komisaris', 'nama' => 'Nama Komisaris, S.T., M.M.', 'urutan' => 2],
        ];
        foreach ($pengurus as $p) {
            // Dicocokkan lewat jabatan + kelompok, jadi data contoh lama ikut diperbarui
            PengurusPerusahaan::updateOrCreate(
                ['jabatan' => $p['jabatan'], 'kelompok' => $p['kelompok']],
                $p + ['latar_belakang' => $latar, 'pendidikan' => $pendidikan, 'foto' => '/images/contoh/pengurus.svg']
            );
        }
    }
}
