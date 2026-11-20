<?php

namespace Database\Seeders;

use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use Illuminate\Database\Seeder;

class ProdukTabunganSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            [
                'judul' => 'TAMASYA',
                'slug' => 'tamasya',
                'tagline' => 'Tabungan Masyarakat yang fleksibel untuk berbagai kalangan.',
                'deskripsi' => 'Tabungan masyarakat dengan akses transaksi di kantor Bank BBJ. Setoran awal minimal Rp50.000 dan setoran berikutnya minimal Rp20.000. Penarikan dapat dilakukan saat kantor beroperasi.',
                'setoran_minimal' => 50000,
                'keunggulan' => "Bunga 3% per tahun sebagaimana tercantum pada informasi produk.\nPenarikan dapat dilakukan saat kantor beroperasi.\nSetoran dapat dijemput melalui layanan pick-up.",
                'persyaratan' => "Fotokopi KTP elektronik\nFotokopi Kartu Keluarga\nMengisi formulir pembukaan rekening",
                'ketentuan' => "Setoran awal minimal Rp50.000.\nSetoran selanjutnya minimal Rp20.000.\nBiaya administrasi dan rekening pasif masing-masing Rp2.000 per bulan.\nPenutupan rekening atas permintaan nasabah dikenai biaya Rp20.000.",
            ],
            [
                'judul' => 'TAKAMUM',
                'slug' => 'takamum',
                'tagline' => 'Tabungan Berjangka Umum untuk membantu merencanakan tujuan finansial.',
                'deskripsi' => 'Tabungan berjangka dengan jangka waktu 1 sampai 7 tahun. Setoran dimulai dari Rp100.000 per bulan dan dilakukan sesuai tanggal perjanjian. Suku bunga mengikuti program yang berlaku.',
                'setoran_minimal' => 100000,
                'keunggulan' => "Pilihan jangka waktu 1 sampai 7 tahun.\nBebas biaya administrasi bulanan.\nSetoran dapat dijemput melalui layanan pick-up.\nTercakup asuransi jiwa apabila tersedia sesuai ketentuan produk.",
                'persyaratan' => "Fotokopi KTP elektronik\nFotokopi Kartu Keluarga\nMengisi formulir pembukaan rekening\nMembuka produk TAMASYA sebagai rekening utama",
                'ketentuan' => "Setoran mulai dari Rp100.000 per bulan.\nPenarikan mengikuti jangka waktu yang disepakati.\nSuku bunga bergantung pada program tahun berjalan.\nTunggakan setoran tiga bulan berturut-turut memengaruhi pemberlakuan bunga program.",
            ],
            [
                'judul' => 'TAS',
                'slug' => 'tas',
                'tagline' => 'Tabungan Anak Sekolah untuk membiasakan anak menabung sejak dini.',
                'deskripsi' => 'Tabungan untuk pelajar dengan setoran awal minimal Rp10.000. Produk ini menawarkan bunga 4% per tahun serta manfaat beasiswa atau hadiah langsung sesuai syarat dan ketersediaan.',
                'setoran_minimal' => 10000,
                'keunggulan' => "Bunga 4% per tahun sebagaimana tercantum pada informasi produk.\nKesempatan memperoleh beasiswa sesuai syarat.\nHadiah perlengkapan sekolah selama persediaan tersedia.\nBebas biaya administrasi bulanan.",
                'persyaratan' => "Fotokopi kartu pelajar atau identitas anak yang setara\nFotokopi KIA atau NIK pada Kartu Keluarga\nFotokopi Kartu Keluarga\nKTP elektronik orang tua atau wali\nMengisi formulir pembukaan rekening",
                'ketentuan' => "Setoran awal minimal Rp10.000.\nSetoran berikutnya minimal Rp5.000.\nPenarikan tabungan anak memerlukan persetujuan orang tua.",
            ],
            [
                'judul' => 'TAPEN',
                'slug' => 'tapen',
                'tagline' => 'Tabungan Pendidikan dengan setoran terencana untuk kebutuhan pendidikan.',
                'deskripsi' => 'Tabungan pendidikan berjangka dengan pilihan masa menabung 1 sampai 6 tahun. Setoran dimulai dari Rp100.000 per bulan; suku bunga mengikuti program yang berlaku.',
                'setoran_minimal' => 100000,
                'keunggulan' => "Pilihan jangka waktu 1 sampai 6 tahun.\nBebas biaya administrasi bulanan.\nSetoran dapat dijemput melalui layanan pick-up.\nTercakup asuransi jiwa apabila tersedia sesuai ketentuan produk.",
                'persyaratan' => "Fotokopi kartu pelajar atau identitas anak yang setara\nKTP elektronik orang tua atau wali\nKartu Keluarga dan/atau surat nikah\nMengisi formulir dan surat pernyataan\nMembuka produk TAMASYA sebagai rekening utama",
                'ketentuan' => "Setoran mulai dari Rp100.000 per bulan.\nPenarikan mengikuti jangka waktu yang disepakati.\nSuku bunga bergantung pada program tahun berjalan.\nTunggakan setoran tiga bulan berturut-turut memengaruhi pemberlakuan bunga program.",
            ],
            [
                'judul' => 'SAJADAH',
                'slug' => 'sajadah',
                'tagline' => 'Simpanan Perjalanan Ibadah untuk membantu merencanakan perjalanan ke tanah suci.',
                'deskripsi' => 'Simpanan dengan setoran sesuai jadwal perjanjian untuk membantu nasabah mempersiapkan perjalanan ibadah. Dana disimpan sampai waktu yang ditentukan dan penarikan mengikuti ketentuan produk.',
                'setoran_minimal' => 100000,
                'keunggulan' => "Simpanan dijamin LPS sesuai ketentuan yang berlaku.\nBebas biaya administrasi bulanan.\nKesempatan memperoleh bonus keberangkatan dan pilihan travel rekanan sesuai ketentuan.\nSetoran dapat dijemput melalui layanan pick-up.",
                'persyaratan' => "Fotokopi KTP\nFotokopi Kartu Keluarga\nMengisi formulir dan perjanjian pembukaan rekening\nSetoran awal Rp100.000",
                'ketentuan' => "Setoran mengikuti jadwal yang disepakati.\nDana disimpan selama jangka waktu yang ditetapkan.\nPenarikan dan pembatalan mengikuti ketentuan produk.",
            ],
        ];

        foreach ($produk as $item) {
            $row = $item + [
                'jenis' => 'tabungan',
                'deskripsi_rata' => 'rata',
                'foto_utama' => null,
                'flyer' => null,
                'brosur_pdf' => null,
                'diterbitkan' => true,
            ];

            $model = ProdukBank::firstOrCreate(['slug' => $item['slug']], $row);

            $bunga = match ($item['slug']) {
                'tamasya' => [12 => 3.00],
                'tas' => [12 => 4.00],
                'takamum' => [12 => 3.00],
                'tapen' => [12 => 2.80],
                default => [],
            };

            foreach ($bunga as $tenor => $nilai) {
                SukuBungaDeposito::firstOrCreate(
                    ['produk_bank_id' => $model->id, 'tenor_bulan' => $tenor],
                    ['bunga' => $nilai],
                );
            }
        }
    }
}
