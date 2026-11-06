<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    // Daftar berita: /berita
    public function index()
    {
        $beritas = Berita::orderByDesc('tanggal')->get();

        return view('berita.index', compact('beritas'));
    }

    // Satu berita: /berita/{slug}
    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        // Berita terbaru lainnya (selain yang sedang dibuka).
        // Desktop menampilkan 3 pertama; smartphone bisa menggeser sampai 6.
        $lainnya = Berita::where('id', '!=', $berita->id)
            ->orderByDesc('tanggal')
            ->take(6)
            ->get();

        return view('berita.show', compact('berita', 'lainnya'));
    }
}
