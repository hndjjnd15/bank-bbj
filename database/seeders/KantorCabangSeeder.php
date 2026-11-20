<?php

namespace Database\Seeders;

use App\Models\KantorCabang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KantorCabangSeeder extends Seeder
{
    public function run(): void
    {
        $jamOperasional = null;
        $data = [
            [
                'nama' => 'Kantor Pusat Parungpanjang',
                'tipe' => 'Kantor Pusat',
                'kota' => 'Kabupaten Bogor',
                'alamat' => 'Jl. Mohamad Toha No. 80, Cibunar, Kec. Parung Panjang, Kabupaten Bogor, Jawa Barat 16360',
                'telepon' => '(021) 5979127',
                'whatsapp' => '6281110021707',
                'email' => 'sobatcare@bprbogorjabar.co.id',
                'link_maps' => 'https://maps.app.goo.gl/MDMi72DV8yWH6V7R9',
            ],
            [
                'nama' => 'Kantor Cabang Leuwiliang',
                'tipe' => 'Kantor Cabang',
                'kota' => 'Kabupaten Bogor',
                'alamat' => 'Jl. Raya Leuwiliang, Kp. Warnasari RT 001 RW 001, Desa Cibeber 1, Kecamatan Leuwiliang, Bogor 16640',
                'telepon' => '(0251) 8640055',
                'whatsapp' => '6281110022707',
                'email' => 'bankbbjleuwiliang@gmail.com',
                'link_maps' => 'https://maps.app.goo.gl/g3SiezRo2A7pX4MCA',
            ],
            [
                'nama' => 'Kantor Cabang Sawangan',
                'tipe' => 'Kantor Cabang',
                'kota' => 'Kota Depok',
                'alamat' => 'Jl. Abdul Wahab No. 6, Kecamatan Sawangan, Kota Depok',
                'telepon' => '(0251) 8613792',
                'whatsapp' => '6281110023707',
                'email' => 'bankbbjsawangan@gmail.com',
                'link_maps' => 'https://maps.app.goo.gl/g7dzMP2DTB6d8isc6',
            ],
            [
                'nama' => 'Kantor Cabang Citeureup',
                'tipe' => 'Kantor Cabang',
                'kota' => 'Kabupaten Bogor',
                'alamat' => 'Jl. Raya Alternatif Puspanegara No. 101, Kecamatan Citeureup, Kabupaten Bogor',
                'telepon' => '(021) 87942230',
                'whatsapp' => '6281110024707',
                'email' => 'bankbbjciteureup@gmail.com',
                'link_maps' => 'https://maps.app.goo.gl/o7w8wKF2C63ucDJH8',
            ],
        ];

        foreach ($data as $urutan => $kantor) {
            $kantor['slug'] = Str::slug($kantor['nama']);
            $kantor['jam_operasional'] = $jamOperasional;
            $kantor['koordinat'] = null;
            $kantor['foto_utama'] = null;
            $kantor['galeri'] = null;
            $kantor['urutan'] = $urutan + 1;
            $kantor['diterbitkan'] = true;

            KantorCabang::updateOrCreate(['slug' => $kantor['slug']], $kantor);
        }
    }
}
