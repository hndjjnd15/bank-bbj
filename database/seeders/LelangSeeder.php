<?php

namespace Database\Seeders;

use App\Models\Lelang;
use Illuminate\Database\Seeder;

class LelangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kantor-bbj', 'Kantor BBJ', 'Bangunan Sertifikat', 'JL Raya Bojong Gede, Desa Rawa Panjang, Kec. Bojong Gede, Kab. Bogor, Jawa Barat', 'Bojong Gede, Kota Bogor', 246, 123, '-6.469053, 106.803942', 1300000000],
            ['asniati', 'Asniati', 'Bangunan Sertifikat', 'Jl. Jeruk IV No. 23 RT 04 RW 11, Desa Parungpanjang, Kec. Parungpanjang, Kab. Bogor', 'Parungpanjang, Kab. Bogor', 84, 80, '-6.353527, 106.564764', 290000000],
            ['ida-siti-khodijah', 'Ida Siti Khodijah', 'Bangunan Sertifikat', 'Jl. Kranji IV No. 50 RT 06 RW 06, Kel. Ciganjur, Kec. Jagakarsa, Jakarta', 'Jagakarsa, Jakarta', 111, 185, '-6.338750, 106.802389', 1200000000],
            ['siti-fujianti', 'Siti Fujianti', 'Bangunan Sertifikat', 'Jl. Babakan RT 002/004, Ciherang Pondok, Caringin, Kab. Bogor', 'Ciherang Pondok, Kab. Bogor', 279, 200, '-6.683250, 106.828139', 400000000],
            ['andika-raharja', 'Andika Raharja', 'Bangunan Sertifikat', 'Kp. Hambaro RT 003/007, Desa Hambaro, Kec. Nanggung, Bogor', 'Nanggung, Kab. Bogor', 326, 392, '-6.572280, 106.572853', 900000000],
            ['cahyati', 'Cahyati', 'Bangunan Sertifikat', 'Kp. Cibeber RT 16 RW 06, Desa Cikahuripan, Kec. Klapanunggal, Kab. Bogor', 'Cikahuripan, Kota Bogor', 105, 95, '-6.445820, 106.978620', 450000000],
            ['cv-wira', 'CV Wira Prakarsa', 'Rumah Komplek Sertifikat', 'Perumahan Kostrad Cijujung Permai Blok G No. 16, Desa Cijujung, Kec. Sukaraja, Kab. Bogor', 'Cijujung, Kab. Bogor', 120, 95, '-6.529268, 106.840013', 500000000],
            ['dian-ahmad-yani-rumah', 'Dian Ahmad Yani - Rumah', 'Rumah Sertifikat', 'Kp. Tarikolot RT 02 RW 01, Desa Mekar Jaya, Cigudeg, Bogor', 'Cigudeg, Kab. Bogor', 239, 70, '-6.502320, 106.502540', 400000000],
            ['dwi-indra-sani', 'Dwi Indra Sani', 'Rumah Sertifikat', 'Jl. Babakan Indah RT 02 RW 02, Desa Banjarwaru, Kec. Ciawi, Kab. Bogor', 'Ciawi, Kab. Bogor', 193, 80, '-6.665230, 106.855220', 500000000],
            ['eka-supriatiningsih', 'Eka Supriatiningsih', 'Rumah 2 Lantai', 'Jl. Melahayu Blok C4 No. 27 RT 002 RW 004, Kel. Harapan Jaya, Kec. Cibinong, Bogor', 'Cibinong, Kab. Bogor', 90, 90, '-6.468243, 106.834309', 900000000],
            ['fadlun-s', 'Fadlun Shofiyah', 'Rumah Sertifikat', 'Perumahan Kopwani Village 2 Blok O No. 05, Kel. Kali Baru, Kec. Cilodong, Kota Depok', 'Cilodong, Kota Depok', 72, 42, '-6.440454, 106.834894', 500000000],
            ['nurdin', 'Nurdin', 'Tanah Perkebunan', 'Jl. Kampung Muara RT 03 RW 10, Ciburuy, Kec. Cigombong, Bogor', 'Cigombong, Kab. Bogor', 985, null, '-6.730737, 106.808538', 250000000],
            ['nurul-mustika', 'Nurul Mustika', 'Rumah 2 Lantai', 'Jl. Abdul Gani RT 04 RW 02, Kel. Kali Baru, Kec. Cilodong, Kota Depok', 'Cilodong, Kota Depok', 72, 120, '-6.445820, 106.978620', 435000000],
            ['pt-alfatree', 'PT Alfatree', 'Rumah Mewah 2 Lantai', 'Gunung Puyuh, Sukabumi', 'Gunung Puyuh, Sukabumi', 858, null, null, 5000000000],
            ['wahyudin', 'Wahyudin', 'Rumah Sertifikat', 'Kp. Masjid No. 26 RT 02 RW 04, Desa Bojonggede, Kec. Bojonggede, Kab. Bogor', 'Bojong Gede, Kab. Bogor', 203, 152, '-6.490075, 106.796637', 431100000],
            ['ramdhani', 'Ramdhani', 'Rumah Sertifikat', 'Kp. Sawah Baru, Jl. Sawah Baru RT 002 RW 008, Desa Laladon, Kec. Ciomas, Kab. Bogor', 'Ciomas, Kota Bogor', 239, 54, '-6.588201, 106.753136', 700000000],
            ['gustina', 'Gustina', 'Rumah Sertifikat', 'Kavling Serpong TR 004 RW 004, Desa Serpong, Kec. Serpong, Tangerang Selatan', 'Serpong, Tangerang Selatan', 170, 165, '-6.322598, 106.662357', 800000000],
            ['aom-ruslan', 'Aom Ruslan', 'Tanah Sertifikat', 'Kp. Cicadas RT 004/005, Desa Cicadas, Kec. Gunung Putri, Kab. Bogor', 'Gunung Putri, Kab. Bogor', 1525, null, '-6.434270, 106.921147', 686250000],
            ['ismawati', 'Ismawati', 'Bangunan Sertifikat', 'Kampung Karekel RT 001 RW 006, Desa Pingku, Kec. Parung Panjang, Kab. Bogor', 'Kampung Karekel, Kab. Bogor', 484, null, '-6.4028406, 106.5983885', 380000000],
            ['randi-suprihadi', 'Randi Suprihadi', 'Bangunan Sertifikat', 'Jl. Setapak No. 21 C RT 05/09, Kel. Cakung Barat, Kec. Cakung, Jakarta Timur', 'Jakarta Timur', 154, 252, '-6.161899, 106.933470', 590000000],
            ['dian-ahmad-yani-tanah', 'Dian Ahmad Yani - Tanah', 'Tanah Sertifikat', 'Kp. Tarikolot RT 02 RW 01, Desa Mekar Jaya, Cigudeg, Bogor', 'Cigudeg, Kab. Bogor', 132, null, '-6.502320, 106.502540', 105600000],
        ];

        foreach ($data as [$slug, $nama, $tipe, $alamat, $kota, $luasTanah, $luasBangunan, $koordinat, $hargaLimit]) {
            Lelang::updateOrCreate(['slug' => $slug], [
                'judul' => $nama.' - '.$tipe,
                'tipe_aset' => $tipe,
                'sertifikat' => 'Informasi sertifikat dapat dikonfirmasi kepada Bank BBJ.',
                'status' => 'lelang',
                'jadwal_lelang' => null,
                'luas_tanah' => $luasTanah,
                'luas_bangunan' => $luasBangunan,
                'alamat' => $alamat,
                'kota' => $kota,
                'koordinat' => $koordinat,
                'link_maps' => null,
                'harga_limit' => $hargaLimit,
                'harga_pasar' => null,
                'pic_nama' => 'Bank BBJ',
                'pic_whatsapp' => '6281110026707',
                'link_instagram' => null,
                'deskripsi' => 'Aset jaminan yang ditawarkan Bank BBJ. Harga, ketersediaan, jenis sertifikat, dan jadwal peninjauan perlu dikonfirmasi kembali kepada Bank BBJ sebelum transaksi.',
                'foto_utama' => null,
                'galeri' => null,
            ]);
        }
    }
}
