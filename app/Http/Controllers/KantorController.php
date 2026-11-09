<?php

namespace App\Http\Controllers;

use App\Models\KantorCabang;
use Illuminate\Http\Request;

class KantorController extends Controller
{
    // Urutan SQL berdasarkan tipe: Kantor Pusat, Kantor Cabang, lalu Kantor Kas
    private function urutTipe($query)
    {
        $daftar = "'" . implode("','", KantorCabang::TIPE) . "'";

        return $query->orderByRaw("FIELD(tipe, {$daftar})")->orderBy('urutan')->orderBy('nama');
    }

    // Daftar: /jaringan-kantor  (contoh: ?q=sawangan&tipe=Kantor+Cabang)
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tipe = $request->query('tipe');
        $kota = $request->query('kota');

        $kotaList = KantorCabang::query()->where('diterbitkan', true)->distinct()->orderBy('kota')->pluck('kota')->all();

        // Nilai yang tidak dikenal diabaikan (bukan error)
        $tipe = is_string($tipe) && in_array($tipe, KantorCabang::TIPE, true) ? $tipe : null;
        $kota = is_string($kota) && in_array($kota, $kotaList, true) ? $kota : null;

        $kantors = $this->urutTipe(
            KantorCabang::query()
                ->where('diterbitkan', true)
                ->when($q !== '', function ($query) use ($q) {
                    $cari = '%' . addcslashes($q, '%_\\') . '%';
                    $query->where(fn ($w) => $w
                        ->where('nama', 'like', $cari)
                        ->orWhere('alamat', 'like', $cari)
                        ->orWhere('kota', 'like', $cari));
                })
                ->when($tipe, fn ($query) => $query->where('tipe', $tipe))
                ->when($kota, fn ($query) => $query->where('kota', $kota))
        )->get();

        return view('kantor.index', [
            'kantors' => $kantors,
            'kotaList' => $kotaList,
            'filter' => compact('q', 'tipe', 'kota'),
            'adaFilter' => $q !== '' || $tipe || $kota,
        ]);
    }

    // Detail: /jaringan-kantor/{slug}
    public function show($slug)
    {
        $kantor = KantorCabang::where('slug', $slug)->where('diterbitkan', true)->firstOrFail();

        $lainnya = $this->urutTipe(
            KantorCabang::where('diterbitkan', true)->where('id', '!=', $kantor->id)
        )->take(6)->get();

        return view('kantor.show', compact('kantor', 'lainnya'));
    }
}
