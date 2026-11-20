<?php

namespace Database\Seeders;

use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use Illuminate\Database\Seeder;

class ProdukDepositoSeeder extends Seeder
{
    public function run(): void
    {
        $produk = ProdukBank::updateOrCreate(['slug' => 'deposito'], [
            'jenis' => 'deposito',
            'judul' => 'Deposito Reguler',
            'tagline' => 'Simpanan berjangka dengan pilihan tenor 3, 6, dan 12 bulan.',
            'deskripsi' => "Deposito Reguler Bank BBJ adalah simpanan berjangka dengan pilihan tenor 3, 6, atau 12 bulan. Bunga dibayarkan saat jatuh tempo dan nasabah dapat memilih fasilitas perpanjangan otomatis (ARO).\n\nPencairan dilakukan pada saat jatuh tempo. Pencairan lebih awal dapat dikenai penalti. Tingkat bunga dan penjaminan mengikuti ketentuan Bank BBJ dan LPS yang berlaku.",
            'deskripsi_rata' => 'rata',
            'setoran_minimal' => null,
            'keunggulan' => "Pilihan tenor 3, 6, dan 12 bulan\nFasilitas perpanjangan otomatis (ARO)\nBunga dibayarkan saat jatuh tempo\nSimpanan dijamin LPS sesuai ketentuan yang berlaku",
            'persyaratan' => "Fotokopi KTP elektronik\nFotokopi Kartu Keluarga\nMengisi formulir pembukaan deposito",
            'ketentuan' => "Pencairan dilakukan pada saat jatuh tempo.\nPencairan sebelum jatuh tempo dapat dikenai penalti.\nPajak bunga mengikuti peraturan pemerintah.\nSuku bunga mengikuti ketentuan Bank BBJ dan LPS yang berlaku.",
            'foto_utama' => null,
            'flyer' => null,
            'brosur_pdf' => null,
            'diterbitkan' => true,
        ]);

        SukuBungaDeposito::query()
            ->where('produk_bank_id', $produk->id)
            ->delete();

        foreach ([3 => 5.75, 6 => 6.00, 12 => 6.25] as $tenor => $bunga) {
            SukuBungaDeposito::create([
                'produk_bank_id' => $produk->id,
                'tenor_bulan' => $tenor,
                'bunga' => $bunga,
            ]);
        }
    }
}
