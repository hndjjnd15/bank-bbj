<?php

namespace App\Http\Controllers;

use App\Models\PemegangSaham;
use App\Models\PengurusPerusahaan;
use App\Models\ProfilPerusahaan;

class ProfilController extends Controller
{
    // Profil perusahaan: /profil-perusahaan
    public function index()
    {
        $profil = ProfilPerusahaan::query()->first();

        $pemegangSaham = PemegangSaham::query()->orderBy('urutan')->orderBy('id')->get();
        $totalDasar = (float) $pemegangSaham->sum('modal_dasar');
        $totalDisetor = (float) $pemegangSaham->sum('modal_disetor');

        // Pengurus dikelompokkan sesuai urutan di PengurusPerusahaan::KELOMPOK
        $pengurus = PengurusPerusahaan::query()->orderBy('urutan')->orderBy('id')->get()->groupBy('kelompok');

        return view('profil.index', compact('profil', 'pemegangSaham', 'totalDasar', 'totalDisetor', 'pengurus'));
    }
}
