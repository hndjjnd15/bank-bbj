<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;

class ProdukController extends Controller
{
    public function tabungan()
    {
        $produk = ProdukBank::query()
            ->where('jenis', 'tabungan')
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->get();

        return view('produk.tabungan.index', compact('produk'));
    }

    public function tabunganDetail(string $slug)
    {
        $produk = ProdukBank::query()
            ->where('jenis', 'tabungan')
            ->where('diterbitkan', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $faq = Faq::query()
            ->terbit()
            ->where('halaman', 'tabungan')
            ->get();

        $lainnya = ProdukBank::query()
            ->where('jenis', 'tabungan')
            ->where('diterbitkan', true)
            ->where('id', '!=', $produk->id)
            ->orderBy('id')
            ->get();

        $sukuBunga = $produk->sukuBunga()->orderBy('tenor_bulan')->get();
        $jadwalBunga = $sukuBunga->map(fn (SukuBungaDeposito $baris): array => [
            'tenor' => (int) $baris->tenor_bulan,
            'bunga' => (float) $baris->bunga,
        ])->values()->all();

        return view('produk.tabungan.show', compact('produk', 'faq', 'lainnya', 'sukuBunga', 'jadwalBunga'));
    }

    public function kredit()
    {
        $produk = ProdukBank::query()
            ->where('jenis', 'kredit')
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->get();

        return view('produk.kredit.index', compact('produk'));
    }

    public function kreditDetail(string $slug)
    {
        $produk = ProdukBank::query()
            ->where('jenis', 'kredit')
            ->where('diterbitkan', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $faq = Faq::query()
            ->terbit()
            ->where('halaman', 'kredit')
            ->where('produk_bank_id', $produk->id)
            ->get();

        $lainnya = ProdukBank::query()
            ->where('jenis', 'kredit')
            ->where('diterbitkan', true)
            ->where('id', '!=', $produk->id)
            ->orderBy('id')
            ->get();

        return view('produk.kredit.show', compact('produk', 'faq', 'lainnya'));
    }

    // Halaman deposito: /deposito
    public function deposito()
    {
        $produk = ProdukBank::where('jenis', 'deposito')
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->first();

        abort_if($produk === null, 404);

        $faq = Faq::query()
            ->terbit()
            ->where('halaman', 'deposito')
            ->get();

        $baris = $produk->sukuBunga()->orderBy('tenor_bulan')->get();
        $tenor = $baris->pluck('tenor_bulan')->map(fn ($t) => (int) $t)->all();

        // Angka ringkasan dihitung dari data, bukan diketik manual
        $ringkasan = [
            'tenor_terpendek' => $tenor ? min($tenor) : null,
            'tenor_terpanjang' => $tenor ? max($tenor) : null,
            'bunga_tertinggi' => $baris->max('bunga'),
            'nominal_terkecil' => (int) ($produk->setoran_minimal ?? 0),
        ];

        // Data untuk kalkulator di browser
        $dataKalkulator = $baris->map(fn ($r) => [
            'tenor' => (int) $r->tenor_bulan,
            'bunga' => (float) $r->bunga,
        ])->values()->all();

        return view('produk.deposito', compact('produk', 'faq', 'baris', 'tenor', 'ringkasan', 'dataKalkulator'));
    }
}
