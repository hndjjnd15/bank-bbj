<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use Illuminate\Database\Seeder;

class ProdukDepositoSeeder extends Seeder
{
    public function run(): void
    {
        // Semua isi di bawah hanya CONTOH (termasuk angka suku bunga). Ganti dengan data asli lewat /admin
        // sebelum website tayang.
        $faq = [
            ['tanya' => 'Apa itu deposito?', 'jawab' => 'Deposito adalah simpanan berjangka yang penarikannya hanya dapat dilakukan pada waktu tertentu sesuai perjanjian antara nasabah dan bank.'],
            ['tanya' => 'Bagaimana cara membuka deposito?', 'jawab' => 'Kunjungi kantor cabang terdekat dengan membawa dokumen persyaratan yang tercantum pada halaman ini, lalu lakukan penempatan dana sesuai nominal dan jangka waktu yang dipilih. (Jawaban contoh, sesuaikan dengan prosedur bank.)'],
            ['tanya' => 'Berapa setoran minimal deposito?', 'jawab' => 'Setoran minimal tercantum pada informasi produk deposito di halaman ini.'],
            ['tanya' => 'Apakah bunga deposito dikenai pajak?', 'jawab' => 'Bunga deposito dikenai PPh final sebesar '.rtrim(rtrim(number_format((float) config('deposito.pajak_persen'), 2, ',', '.'), '0'), ',').'% dari bunga, kecuali untuk deposito dengan nominal tidak lebih dari Rp '.number_format((float) config('deposito.batas_bebas_pajak'), 0, ',', '.').'. Gunakan kalkulator pada halaman ini untuk melihat perkiraannya.'],
            ['tanya' => 'Apakah deposito dijamin LPS?', 'jawab' => (string) config('deposito.lps_catatan')],
            ['tanya' => 'Apakah deposito bisa dicairkan sebelum jatuh tempo?', 'jawab' => 'Ketentuan pencairan sebelum jatuh tempo mengikuti kebijakan bank. Silakan hubungi kantor cabang terdekat untuk penjelasan lengkap. (Jawaban contoh, sesuaikan dengan kebijakan bank.)'],
        ];

        $produkDeposito = ProdukBank::updateOrCreate(['slug' => 'deposito'], [
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

        // Satu baris FAQ hanya punya satu penempatan, jadi pertanyaan yang sama
        // disimpan sekali untuk beranda dan sekali untuk halaman deposito.
        foreach ($faq as $urutan => $item) {
            foreach (['beranda', 'deposito'] as $halaman) {
                Faq::firstOrCreate(
                    ['halaman' => $halaman, 'tanya' => $item['tanya']],
                    ['jawab' => $item['jawab'], 'urutan' => $urutan, 'diterbitkan' => true],
                );
            }
        }

        // Jadwal suku bunga CONTOH; sesuaikan dengan data resmi melalui /admin
        SukuBungaDeposito::query()->where('produk_bank_id', $produkDeposito->id)->delete();
        $bunga = [
            1 => 3.50,
            3 => 3.75,
            6 => 4.00,
            12 => 4.50,
            24 => 4.75,
        ];
        foreach ($bunga as $tenor => $nilai) {
            SukuBungaDeposito::create([
                'produk_bank_id' => $produkDeposito->id,
                'tenor_bulan' => $tenor,
                'bunga' => $nilai,
            ]);
        }
    }
}
