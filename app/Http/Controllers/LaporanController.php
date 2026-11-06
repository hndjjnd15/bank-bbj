<?php

namespace App\Http\Controllers;

use App\Models\LaporanTahunan;
use App\Models\LaporanTriwulan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Laporan keuangan: /laporan  (tab awal: /laporan?jenis=tahunan)
    public function index(Request $request)
    {
        $tab = $request->query('jenis') === 'tahunan' ? 'tahunan' : 'triwulan';

        // Triwulan dikelompokkan per tahun (terbaru di atas), periode terbaru di atas
        $triwulan = LaporanTriwulan::query()
            ->orderByDesc('tahun')
            ->orderByDesc('periode')
            ->get()
            ->groupBy('tahun');

        $tahunan = LaporanTahunan::query()->orderByDesc('tahun')->get();

        return view('laporan.index', compact('tab', 'triwulan', 'tahunan'));
    }
}
