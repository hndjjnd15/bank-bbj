<?php

namespace Database\Seeders;

use App\Models\Lelang;
use Illuminate\Database\Seeder;

class LelangSeeder extends Seeder
{
    public function run(): void
    {
        // Semua data di bawah hanya CONTOH untuk uji tampilan. Hapus lewat /admin sebelum website tayang.
        $dasar = [
            'sertifikat' => 'SHM (Sertifikat Hak Milik)',
            'pic_nama' => 'Marketing Lelang',
            'pic_whatsapp' => '081234567890',
            'link_instagram' => null,
            'deskripsi' => "Data contoh.\n\nKondisi fisik terawat, akses jalan dapat dilalui kendaraan roda empat, lingkungan strategis dan dekat fasilitas umum.\n\nHubungi PIC untuk informasi lebih lanjut dan jadwal survei lokasi.",
        ];

        $data = [
            ['judul' => 'Rumah Tinggal Strategis di Parungpanjang', 'tipe_aset' => 'Rumah Tinggal', 'status' => 'lelang', 'jadwal_lelang' => '2026-12-10 10:00:00', 'luas_tanah' => 120, 'luas_bangunan' => 84, 'alamat' => 'Jl. Contoh No. 12, Parungpanjang', 'kota' => 'Kabupaten Bogor', 'koordinat' => '-6.3694, 106.5603', 'harga_limit' => 850000000, 'harga_pasar' => 950000000, 'link_instagram' => 'https://www.instagram.com/', 'foto_utama' => '/images/contoh/foto-1.svg', 'galeri' => ['/images/contoh/foto-2.svg', '/images/contoh/foto-3.svg']],
            ['judul' => 'Tanah Kavling Siap Bangun di Leuwiliang', 'tipe_aset' => 'Tanah Kosong', 'status' => 'ayda', 'luas_tanah' => 300, 'alamat' => 'Jl. Contoh Raya, Leuwiliang', 'kota' => 'Kabupaten Bogor', 'harga_limit' => 420000000, 'harga_pasar' => 480000000, 'foto_utama' => '/images/contoh/foto-2.svg', 'galeri' => ['/images/contoh/foto-3.svg']],
            ['judul' => 'Ruko Dua Lantai di Sawangan', 'tipe_aset' => 'Ruko / Rukan', 'status' => 'lelang', 'jadwal_lelang' => '2026-12-18 09:30:00', 'sertifikat' => 'SHGB (Sertifikat Hak Guna Bangunan)', 'luas_tanah' => 96, 'luas_bangunan' => 160, 'alamat' => 'Jl. Contoh Sawangan No. 5', 'kota' => 'Kota Depok', 'harga_limit' => 1350000000, 'harga_pasar' => 1500000000, 'foto_utama' => '/images/contoh/foto-3.svg', 'galeri' => ['/images/contoh/foto-1.svg']],
            ['judul' => 'Rumah Cluster Minimalis di Citeureup', 'tipe_aset' => 'Rumah Tinggal', 'status' => 'ayda', 'luas_tanah' => 72, 'luas_bangunan' => 45, 'alamat' => 'Perumahan Contoh Blok C-7, Citeureup', 'kota' => 'Kabupaten Bogor', 'harga_limit' => 520000000, 'foto_utama' => '/images/contoh/foto-1.svg'],
            ['judul' => 'Gudang Pergudangan di Parungpanjang', 'tipe_aset' => 'Gudang', 'status' => 'lelang', 'luas_tanah' => 600, 'luas_bangunan' => 420, 'alamat' => 'Kawasan Pergudangan Contoh, Parungpanjang', 'kota' => 'Kabupaten Bogor', 'harga_limit' => 2800000000, 'harga_pasar' => 3100000000, 'foto_utama' => '/images/contoh/foto-2.svg'],
            ['judul' => 'Tanah Pekarangan di Leuwiliang', 'tipe_aset' => 'Tanah Kosong', 'status' => 'terjual', 'luas_tanah' => 150, 'alamat' => 'Kp. Contoh RT 02/03, Leuwiliang', 'kota' => 'Kabupaten Bogor', 'harga_limit' => 210000000, 'foto_utama' => '/images/contoh/foto-3.svg'],
            ['judul' => 'Apartemen Studio di Depok', 'tipe_aset' => 'Apartemen', 'status' => 'ayda', 'sertifikat' => 'SHMSRS (Hak Milik Satuan Rumah Susun)', 'luas_bangunan' => 28, 'alamat' => 'Apartemen Contoh Tower B Lt. 10', 'kota' => 'Kota Depok', 'harga_limit' => 330000000, 'harga_pasar' => 365000000, 'foto_utama' => '/images/contoh/foto-1.svg'],
        ];

        foreach ($data as $d) {
            $row = array_merge($dasar, $d);
            $row['slug'] = \Illuminate\Support\Str::slug($row['judul']);
            Lelang::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
