<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $paragraf = "Contoh isi berita. Paragraf ini hanya teks pengisi agar halaman cukup panjang untuk melihat promo di samping ikut turun saat halaman di-scroll. Teks asli nanti diisi dari panel admin.";
        $panjang = implode("\n\n", array_fill(0, 9, $paragraf));

        $data = [
            [
                'judul' => 'Promo Tapena Cemerlang: Bonus Bunga 2%',
                'slug' => 'promo-tapena-cemerlang-bonus-bunga-2',
                'ringkasan' => 'Siapkan masa depan pendidikan anak dengan bonus bunga di akhir periode.',
                'isi' => "Bank Serang menghadirkan promo Tapena Cemerlang dengan bonus bunga 2% di akhir periode, berlaku sampai 31 Desember 2026.\n\n" . $panjang . "\n\nSimpanan nasabah dijamin LPS sesuai ketentuan yang berlaku.",
                'foto' => ['/image/contoh/foto-1.svg', '/image/contoh/foto-2.svg', '/image/contoh/foto-3.svg'],
                'tanggal' => '2026-10-07',
            ],
            [
                'judul' => 'Tips Memilih Deposito yang Aman',
                'slug' => 'tips-memilih-deposito-yang-aman',
                'ringkasan' => 'Pastikan bank terdaftar OJK dan simpanan dijamin LPS.',
                'isi' => "Periksa tingkat bunga penjaminan LPS dan pastikan bunga yang ditawarkan tidak melebihi batas yang berlaku.\n\n" . $panjang,
                'foto' => ['/image/contoh/foto-2.svg', '/image/contoh/foto-3.svg'],
                'tanggal' => '2026-10-03',
            ],
            [
                'judul' => 'Kredit untuk UMKM Serang',
                'slug' => 'kredit-untuk-umkm-serang',
                'ringkasan' => 'Permodalan cepat dan mudah untuk pelaku usaha di Serang dan sekitarnya.',
                'isi' => "Bank Serang mendukung UMKM melalui berbagai produk kredit dengan proses yang jelas dan transparan.\n\n" . $paragraf,
                'foto' => null, // contoh berita tanpa foto
                'tanggal' => '2026-09-25',
            ],
            [
                'judul' => 'Layanan Bank Serang di Hari Kerja',
                'slug' => 'layanan-bank-serang-di-hari-kerja',
                'ringkasan' => 'Kantor melayani Senin sampai Jumat pukul 08.00 sampai 16.00 WIB.',
                'isi' => "Nasabah dapat datang langsung ke kantor atau menghubungi call center (0254) 430000 pada jam kerja.\n\n" . $paragraf,
                'foto' => ['/images/contoh/foto-1.svg'],
                'tanggal' => '2026-09-18',
            ],
            [
                'judul' => 'Cara Membuka Tabungan Tamasa',
                'slug' => 'cara-membuka-tabungan-tamasa',
                'ringkasan' => 'Bawa KTP dan setoran awal Rp25.000 ke kantor cabang terdekat.',
                'isi' => "Proses pembukaan rekening sekitar 15 menit, buku tabungan langsung aktif.\n\n" . $paragraf,
                'foto' => ['/images/contoh/foto-3.svg'],
                'tanggal' => '2026-09-10',
            ],
            [
                'judul' => 'Mengenal Penjaminan Simpanan LPS',
                'slug' => 'mengenal-penjaminan-simpanan-lps',
                'ringkasan' => 'Simpanan dijamin hingga Rp 2 miliar per nasabah per bank.',
                'isi' => "Penjaminan berlaku sesuai ketentuan yang berlaku.\n\n" . $paragraf,
                'foto' => ['/images/contoh/foto-2.svg'],
                'tanggal' => '2026-09-02',
            ],
            [
                'judul' => 'Simulasi Angsuran Kredit Sebelum Mengajukan',
                'slug' => 'simulasi-angsuran-kredit-sebelum-mengajukan',
                'ringkasan' => 'Rencanakan angsuran dengan simulasi yang cepat, mudah, dan gratis.',
                'isi' => "Hitung angsuran sebelum mengajukan agar sesuai kemampuan.\n\n" . $paragraf,
                'foto' => ['/images/contoh/foto-1.svg', '/images/contoh/foto-3.svg'],
                'tanggal' => '2026-08-21',
            ],
            [
                'judul' => 'Program CSR Bank Serang untuk Masyarakat',
                'slug' => 'program-csr-bank-serang-untuk-masyarakat',
                'ringkasan' => 'Kegiatan sosial dan pemberdayaan masyarakat di Kabupaten Serang.',
                'isi' => "Bank Serang terus hadir untuk masyarakat melalui berbagai kegiatan.\n\n" . $paragraf,
                'foto' => null,
                'tanggal' => '2026-08-05',
            ],
        ];

        // updateOrCreate: aman dijalankan berulang, data dengan slug sama tidak dobel
        foreach ($data as $d) {
            Berita::updateOrCreate(['slug' => $d['slug']], $d);
        }
    }
}
