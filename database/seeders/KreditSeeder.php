<?php

namespace Database\Seeders;

use App\Models\ProdukBank;
use Illuminate\Database\Seeder;

class KreditSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            ['Kredit UMKM', 'kredit-umkm'],
            ['Kredit PNS, ASN, dan Honorer', 'kredit-pns-asn-honorer'],
            ['Kredit Pegawai', 'kredit-pegawai'],
            ['Kredit MOU', 'kredit-mou'],
            ['Kredit Petani dan Peternak', 'kredit-petani-peternak'],
            ['Kredit Kopiah', 'kredit-kopiah'],
        ];

        foreach ($produk as [$judul, $slug]) {
            ProdukBank::firstOrCreate(
                ['slug' => $slug],
                [
                    'jenis' => 'kredit',
                    'judul' => $judul,
                    'tagline' => 'Informasi produk '.$judul.' dari Bank BBJ.',
                    'deskripsi' => 'Silakan hubungi kantor cabang terdekat untuk mendapatkan informasi mengenai produk, persyaratan, dan ketentuan pembiayaan.',
                    'simulasi_aktif' => false,
                    'biaya_admin_persen' => 0,
                    'diterbitkan' => true,
                ],
            );
        }
    }
}
