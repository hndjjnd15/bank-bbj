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
        ProfilPerusahaan::query()->firstOrCreate([], [
            'sejarah' => "PT BPR Bogor Jabar (Perseroda), dikenal sebagai Bank BBJ, merupakan BPR milik Pemerintah Kabupaten Bogor, Pemerintah Provinsi Jawa Barat, dan PT Bank Pembangunan Daerah Jawa Barat dan Banten (Bank BJB). Bank BBJ mengusung nilai Amanah, Kuat, dan Peduli.\n\nPerusahaan berawal sebagai PD BPR LPK Parungpanjang pada 1 Juli 1972. Setelah memperoleh pengukuhan sebagai Bank Perkreditan Rakyat pada 1999, perusahaan mengalami perubahan nama dan penggabungan dengan BPR milik Pemerintah Kabupaten Bogor pada 2018. Berdasarkan Peraturan Daerah Provinsi Jawa Barat Nomor 6 Tahun 2021 dan Akta Nomor 32 Tahun 2023, badan hukum dan nama perusahaan menjadi PT BPR Bogor Jabar (Perseroda).\n\nBank BBJ berizin dan diawasi Otoritas Jasa Keuangan serta merupakan peserta penjaminan Lembaga Penjamin Simpanan.",
            'foto_gedung' => null,
            'visi' => 'Menjadi Bank Perekonomian Rakyat yang sehat dan terpercaya dalam mendukung pembangunan ekonomi masyarakat Jawa Barat.',
            'misi' => "Mencetak karyawan yang handal dalam perbankan dan berkeadaban dalam pelayanan.\nMenciptakan produk perbankan yang variatif dan inovatif untuk kebutuhan usaha, konsumtif, dan investasi dengan memaksimalkan peran teknologi.\nMelaksanakan fungsi kepatuhan untuk meningkatkan efektivitas tata kelola BPR.\nBerkontribusi terhadap peningkatan pendapatan asli daerah dan membantu UMKM dalam meningkatkan perekonomian.",
            'foto_struktur' => null,
        ]);

        $pemegangSaham = [
            ['nama' => 'Pemda Provinsi Jawa Barat', 'modal_dasar' => 36720000000, 'modal_disetor' => 7000000000, 'urutan' => 1],
            ['nama' => 'Pemda Kabupaten Bogor', 'modal_dasar' => 20880000000, 'modal_disetor' => 9998500000, 'urutan' => 2],
            ['nama' => 'PT Bank BJB', 'modal_dasar' => 14400000000, 'modal_disetor' => 1590000000, 'urutan' => 3],
        ];

        foreach ($pemegangSaham as $pemegang) {
            PemegangSaham::updateOrCreate(['nama' => $pemegang['nama']], $pemegang);
        }

        $pengurus = [
            ['nama' => 'M Roby Binarta', 'jabatan' => 'Direktur Utama', 'kelompok' => 'direksi', 'urutan' => 1],
            ['nama' => 'Fasirun', 'jabatan' => 'Direktur', 'kelompok' => 'direksi', 'urutan' => 2],
            ['nama' => 'Erwin Sugita', 'jabatan' => 'Komisaris Utama', 'kelompok' => 'komisaris', 'urutan' => 1],
        ];

        foreach ($pengurus as $orang) {
            PengurusPerusahaan::updateOrCreate(
                ['jabatan' => $orang['jabatan'], 'kelompok' => $orang['kelompok']],
                $orang + [
                    'foto' => null,
                    'latar_belakang' => null,
                    'pendidikan' => null,
                ],
            );
        }
    }
}
