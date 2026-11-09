<?php

namespace Database\Seeders;

use App\Models\KantorCabang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KantorCabangSeeder extends Seeder
{
    public function run(): void
    {
        // Semua data di bawah hanya CONTOH (alamat, telepon, dan WhatsApp placeholder).
        // Ganti dengan data asli lewat /admin sebelum website tayang.
        $dasar = [
            'jam_operasional' => "Senin - Jumat: 08.00 - 16.00 WIB\nSabtu, Minggu, dan hari libur nasional: tutup",
            'whatsapp' => '081234567890',
            'diterbitkan' => true,
        ];

        $data = [
            ['nama' => 'Kantor Pusat (Contoh)', 'tipe' => 'Kantor Pusat', 'kota' => 'Kabupaten Bogor', 'alamat' => 'Alamat contoh kantor pusat', 'telepon' => '(0251) 000000', 'urutan' => 1, 'foto_utama' => '/images/contoh/foto-1.svg'],
            ['nama' => 'Cabang Parungpanjang (Contoh)', 'tipe' => 'Kantor Cabang', 'kota' => 'Parungpanjang', 'alamat' => 'Alamat contoh, Parungpanjang', 'telepon' => '(0251) 000001', 'urutan' => 1, 'foto_utama' => '/images/contoh/foto-2.svg'],
            ['nama' => 'Cabang Leuwiliang (Contoh)', 'tipe' => 'Kantor Cabang', 'kota' => 'Leuwiliang', 'alamat' => 'Alamat contoh, Leuwiliang', 'telepon' => '(0251) 000002', 'urutan' => 2, 'foto_utama' => '/images/contoh/foto-3.svg'],
            ['nama' => 'Cabang Sawangan (Contoh)', 'tipe' => 'Kantor Cabang', 'kota' => 'Sawangan', 'alamat' => 'Alamat contoh, Sawangan', 'telepon' => '(0251) 000003', 'urutan' => 3, 'foto_utama' => '/images/contoh/foto-1.svg'],
            ['nama' => 'Cabang Citeureup (Contoh)', 'tipe' => 'Kantor Cabang', 'kota' => 'Citeureup', 'alamat' => 'Alamat contoh, Citeureup', 'telepon' => '(0251) 000004', 'urutan' => 4, 'foto_utama' => null],
        ];

        foreach ($data as $d) {
            $row = array_merge($dasar, $d);
            $row['slug'] = Str::slug($row['nama']);
            KantorCabang::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
