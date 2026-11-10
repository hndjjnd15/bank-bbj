<?php

namespace App\Http\Controllers;

use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;

class ProdukController extends Controller
{
    // Halaman deposito: /deposito
    public function deposito()
    {
        $produk = ProdukBank::where('jenis', 'deposito')
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->first();

        abort_if($produk === null, 404);

        // Semua baris suku bunga, urut tenor lalu nominal
        $baris = SukuBungaDeposito::query()->orderBy('tenor_bulan')->orderBy('nominal_min')->get();

        // Baris tabel = tenor; kolom tabel = rentang nominal (satu perwakilan per rentang)
        $tenor = $baris->pluck('tenor_bulan')->map(fn ($t) => (int) $t)->unique()->sort()->values()->all();
        $rentang = $baris->unique(fn ($r) => $r->kunci_rentang)->sortBy('nominal_min')->values();

        // $matriks[tenor][kunci_rentang] = baris suku bunga
        $matriks = [];
        foreach ($baris as $r) {
            $matriks[(int) $r->tenor_bulan][$r->kunci_rentang] = $r;
        }

        // Angka ringkasan dihitung dari data, bukan diketik manual
        $ringkasan = [
            'tenor_terpendek' => $tenor ? min($tenor) : null,
            'tenor_terpanjang' => $tenor ? max($tenor) : null,
            'bunga_tertinggi' => $baris->max('bunga'),
            'nominal_terkecil' => (int) ($baris->min('nominal_min') ?? $produk->setoran_minimal ?? 0),
        ];

        // Data untuk kalkulator di browser
        $dataKalkulator = $baris->map(fn ($r) => [
            'tenor' => (int) $r->tenor_bulan,
            'min' => (int) $r->nominal_min,
            'maks' => $r->nominal_maks !== null ? (int) $r->nominal_maks : null,
            'bunga' => (float) $r->bunga,
        ])->values()->all();

        return view('produk.deposito', compact('produk', 'baris', 'tenor', 'rentang', 'matriks', 'ringkasan', 'dataKalkulator'));
    }
}
