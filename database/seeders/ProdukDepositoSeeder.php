<?php

namespace Database\Seeders;

use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use Illuminate\Database\Seeder;

class ProdukDepositoSeeder extends Seeder
{
    public function run(): void
    {
        // Semua isi di bawah hanya CONTOH (termasuk angka suku bunga). Ganti dengan data asli lewat /admin
        // sebelum website tayang.
        ProdukBank::updateOrCreate(['slug' => 'deposito'], [
            'jenis' => 'deposito',
            'judul' => 'Deposito',
            'tagline' => 'Simpanan berjangka dengan bunga menarik dan tenor yang fleksibel.',
            'deskripsi' => "Teks contoh. Jelaskan produk deposito di panel admin: apa itu deposito, siapa yang cocok memakainya, dan apa keunggulannya.\n\nPisahkan paragraf dengan satu baris kosong.",
            'deskripsi_rata' => 'rata',
            'setoran_minimal' => 5000000,
            'keunggulan' => "Suku bunga kompetitif (contoh)\nPilihan jangka waktu sesuai kebutuhan (contoh)\nSimpanan dijamin LPS sesuai ketentuan (contoh)\nProses pembukaan mudah di kantor cabang (contoh)",
            'persyaratan' => "Warga Negara Indonesia (contoh)\nMembawa KTP dan NPWP bila ada (contoh)\nMengisi formulir pembukaan deposito (contoh)",
            'ketentuan' => "Bunga dibayarkan sesuai kesepakatan saat pembukaan (contoh)\nPencairan sebelum jatuh tempo mengikuti ketentuan bank (contoh)",
            'foto_utama' => '/images/contoh/foto-1.svg',
            'flyer' => ['/images/contoh/flyer.svg'],
            'diterbitkan' => true,
        ]);

        // Tabel suku bunga CONTOH: 5 tenor x 3 rentang nominal
        SukuBungaDeposito::query()->delete();
        $rentang = [
            [5000000, 100000000],
            [100000000, 500000000],
            [500000000, null],
        ];
        $bunga = [
            1 => [3.50, 3.75, 4.00],
            3 => [3.75, 4.00, 4.25],
            6 => [4.00, 4.25, 4.50],
            12 => [4.50, 4.75, 5.00],
            24 => [4.75, 5.00, 5.25],
        ];
        foreach ($bunga as $tenor => $daftar) {
            foreach ($rentang as $i => [$min, $maks]) {
                SukuBungaDeposito::create([
                    'tenor_bulan' => $tenor,
                    'nominal_min' => $min,
                    'nominal_maks' => $maks,
                    'bunga' => $daftar[$i],
                ]);
            }
        }
    }
}
