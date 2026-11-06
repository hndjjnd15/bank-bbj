<?php

namespace App\Http\Controllers;

use App\Models\ProgramCsr;
use Illuminate\Http\Request;

class CsrController extends Controller
{
    // Daftar program: /csr  (contoh: /csr?q=sekolah&kategori=Pendidikan&tahun=2026)
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = $request->query('kategori');
        $tahun = $request->query('tahun');

        $tahunList = ProgramCsr::query()
            ->where('diterbitkan', true)
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->all();

        // Nilai yang tidak dikenal diabaikan (bukan error)
        $kategori = is_string($kategori) && in_array($kategori, ProgramCsr::KATEGORI, true) ? $kategori : null;
        $tahun = is_numeric($tahun) && in_array((int) $tahun, $tahunList, true) ? (int) $tahun : null;

        $programs = ProgramCsr::query()
            ->where('diterbitkan', true)
            ->when($q !== '', function ($query) use ($q) {
                $cari = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(fn ($w) => $w
                    ->where('judul', 'like', $cari)
                    ->orWhere('ringkasan', 'like', $cari)
                    ->orWhere('lokasi', 'like', $cari));
            })
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->when($tahun, fn ($query) => $query->whereYear('tanggal', $tahun))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('csr.index', [
            'programs' => $programs,
            'tahunList' => $tahunList,
            'filter' => compact('q', 'kategori', 'tahun'),
            'adaFilter' => $q !== '' || $kategori || $tahun,
        ]);
    }

    // Detail: /csr/{slug}
    public function show($slug)
    {
        $program = ProgramCsr::where('slug', $slug)->where('diterbitkan', true)->firstOrFail();

        $lainnya = ProgramCsr::where('diterbitkan', true)
            ->where('id', '!=', $program->id)
            ->orderByDesc('tanggal')
            ->take(6)
            ->get();

        return view('csr.show', compact('program', 'lainnya'));
    }
}
