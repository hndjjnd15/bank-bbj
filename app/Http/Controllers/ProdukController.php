<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class ProdukController extends Controller
{
    // Halaman deposito: /deposito
    public function deposito()
    {
        $produk = Produk::where('jenis', 'deposito')
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->first();

        abort_if($produk === null, 404);

        return view('produk.deposito', compact('produk'));
    }
}
