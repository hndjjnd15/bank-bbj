<?php

namespace Database\Seeders;

use App\Models\ProdukBank;
use Illuminate\Database\Seeder;

class KreditSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            [
                'judul' => 'Kredit UMKM',
                'slug' => 'kredit-umkm',
                'tagline' => 'Pembiayaan untuk mendukung kebutuhan modal kerja dan investasi usaha.',
                'deskripsi' => "Bank BBJ menyediakan Kredit UMKM Mikro tanpa agunan bagi nasabah aktif yang telah memiliki tabungan sekurang-kurangnya dua tahun. Tersedia pula pembiayaan UMKM dengan agunan sertifikat tanah atau BPKB kendaraan.\n\nPengajuan mengikuti analisis kelayakan dan survei Bank BBJ. Persyaratan serta skema pembiayaan dapat dikonfirmasi ke kantor cabang.",
            ],
            [
                'judul' => 'Kredit PNS, ASN, dan Honorer',
                'slug' => 'kredit-pns-asn-honorer',
                'tagline' => 'Fasilitas pembiayaan bagi pegawai dengan penghasilan tetap.',
                'deskripsi' => "Fasilitas kredit bagi PNS, ASN, PPPK, dan pegawai honorer. Pembayaran angsuran dapat dilakukan melalui pemotongan penghasilan sesuai persetujuan dan ketentuan Bank BBJ.\n\nPersyaratan berbeda menurut status kepegawaian dan jenis pembiayaan. Hubungi kantor Bank BBJ untuk daftar dokumen yang sesuai.",
            ],
            [
                'judul' => 'Kredit Pegawai',
                'slug' => 'kredit-pegawai',
                'tagline' => 'Pembiayaan bagi pegawai dengan penghasilan tetap.',
                'deskripsi' => 'Kredit Pegawai ditujukan bagi pegawai tetap maupun kontrak yang memenuhi ketentuan Bank BBJ. Skema pembayaran dapat menggunakan pemotongan gaji, dan dokumen kepegawaian menjadi bagian dari persyaratan pengajuan.',
            ],
            [
                'judul' => 'Kredit Kerja Sama (MoU)',
                'slug' => 'kredit-mou',
                'tagline' => 'Pembiayaan bagi karyawan perusahaan atau lembaga mitra.',
                'deskripsi' => 'Fasilitas pembiayaan bagi karyawan perusahaan atau lembaga yang telah menjalin kerja sama dengan Bank BBJ. Struktur pinjaman, persyaratan, dan mekanisme pembayaran ditentukan berdasarkan kesepakatan serta analisis Bank BBJ.',
            ],
            [
                'judul' => 'Kredit Petani dan Peternak',
                'slug' => 'kredit-petani-peternak',
                'tagline' => 'Pembiayaan modal kerja yang menyesuaikan siklus usaha.',
                'deskripsi' => 'Fasilitas pembiayaan untuk pelaku usaha pertanian dan peternakan. Penggunaan dana dapat disesuaikan dengan rencana kebutuhan usaha, seperti pengolahan lahan, pengadaan bibit atau pakan, perawatan, hingga masa panen atau penjualan hasil ternak.',
            ],
            [
                'judul' => 'Kredit KOPIAH',
                'slug' => 'kredit-kopiah',
                'tagline' => 'Kredit Ongkos Perjalanan Ibadah untuk membantu rencana ibadah haji.',
                'deskripsi' => 'KOPIAH (Kredit Ongkos Perjalanan Ibadah) merupakan fasilitas pembiayaan Bank BBJ untuk membantu masyarakat mempersiapkan biaya perjalanan ibadah haji. Persyaratan usia, dokumen, dan ketentuan pembiayaan mengikuti kebijakan Bank BBJ.',
            ],
        ];

        foreach ($produk as $item) {
            ProdukBank::firstOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'jenis' => 'kredit',
                    'deskripsi_rata' => 'rata',
                    'simulasi_aktif' => false,
                    'suku_bunga_kredit' => null,
                    'biaya_admin_persen' => 0,
                    'kontak_whatsapp' => null,
                    'foto_utama' => null,
                    'flyer' => null,
                    'brosur_pdf' => null,
                    'diterbitkan' => true,
                ],
            );
        }
    }
}
