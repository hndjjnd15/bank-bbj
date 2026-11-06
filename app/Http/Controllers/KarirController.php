<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;

class KarirController extends Controller
{
    // Daftar: /karir  (contoh: /karir?q=teller&tipe=Kontrak&penempatan=Cabang+Sawangan)
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tipe = $request->query('tipe');
        $penempatan = $request->query('penempatan');

        $penempatanList = Lowongan::query()->distinct()->orderBy('penempatan')->pluck('penempatan')->all();

        // Nilai yang tidak dikenal diabaikan (bukan error)
        $tipe = is_string($tipe) && in_array($tipe, Lowongan::TIPE_KERJA, true) ? $tipe : null;
        $penempatan = is_string($penempatan) && in_array($penempatan, $penempatanList, true) ? $penempatan : null;

        $lowongans = Lowongan::query()
            ->when($q !== '', function ($query) use ($q) {
                $cari = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(fn ($w) => $w
                    ->where('judul', 'like', $cari)
                    ->orWhere('divisi', 'like', $cari)
                    ->orWhere('penempatan', 'like', $cari));
            })
            ->when($tipe, fn ($query) => $query->where('tipe_kerja', $tipe))
            ->when($penempatan, fn ($query) => $query->where('penempatan', $penempatan))
            // Yang masih dibuka di atas; yang sudah ditutup (dimatikan atau lewat batas) di bawah
            ->orderByRaw('(dibuka = 0 OR (batas_lamaran IS NOT NULL AND batas_lamaran < CURDATE())) ASC')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('karir.index', [
            'lowongans' => $lowongans,
            'penempatanList' => $penempatanList,
            'filter' => compact('q', 'tipe', 'penempatan'),
            'adaFilter' => $q !== '' || $tipe || $penempatan,
        ]);
    }

    // Detail: /karir/{slug}
    public function show($slug)
    {
        $lowongan = Lowongan::where('slug', $slug)->firstOrFail();

        $lainnya = Lowongan::where('id', '!=', $lowongan->id)
            ->orderByRaw('(dibuka = 0 OR (batas_lamaran IS NOT NULL AND batas_lamaran < CURDATE())) ASC')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('karir.show', compact('lowongan', 'lainnya'));
    }
}
