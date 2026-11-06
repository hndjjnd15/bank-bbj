<?php

namespace App\Http\Controllers;

use App\Models\Lelang;
use Illuminate\Http\Request;

class LelangController extends Controller
{
    // Daftar: /lelang  (pencarian, penyaring, urutan, dan halaman)
    // Contoh: /lelang?q=rumah&tipe=Rumah+Tinggal&kota=Kota+Depok&status=ayda&urut=harga_asc
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $tipe = $request->query('tipe');
        $kota = $request->query('kota');
        $urut = $request->query('urut', 'terbaru');

        $kotaList = Lelang::query()->distinct()->orderBy('kota')->pluck('kota')->all();

        // Nilai yang tidak dikenal diabaikan (bukan error)
        $status = is_string($status) && array_key_exists($status, Lelang::STATUS) ? $status : null;
        $tipe = is_string($tipe) && in_array($tipe, Lelang::TIPE_ASET, true) ? $tipe : null;
        $kota = is_string($kota) && in_array($kota, $kotaList, true) ? $kota : null;
        $urut = in_array($urut, ['terbaru', 'harga_asc', 'harga_desc'], true) ? $urut : 'terbaru';

        $lelangs = Lelang::query()
            ->when($q !== '', function ($query) use ($q) {
                $cari = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(fn ($w) => $w
                    ->where('judul', 'like', $cari)
                    ->orWhere('alamat', 'like', $cari)
                    ->orWhere('kota', 'like', $cari));
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($tipe, fn ($query) => $query->where('tipe_aset', $tipe))
            ->when($kota, fn ($query) => $query->where('kota', $kota))
            ->orderByRaw("status = 'terjual'")   // yang sudah terjual selalu di akhir
            ->when($urut === 'harga_asc', fn ($query) => $query->orderBy('harga_limit'))
            ->when($urut === 'harga_desc', fn ($query) => $query->orderByDesc('harga_limit'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();   // filter tetap aktif saat pindah halaman

        return view('lelang.index', [
            'lelangs' => $lelangs,
            'kotaList' => $kotaList,
            'filter' => compact('q', 'status', 'tipe', 'kota', 'urut'),
            'adaFilter' => $q !== '' || $status || $tipe || $kota || $urut !== 'terbaru',
        ]);
    }

    // Detail: /lelang/{slug}
    public function show($slug)
    {
        $lelang = Lelang::where('slug', $slug)->firstOrFail();

        $lainnya = Lelang::where('id', '!=', $lelang->id)
            ->orderByRaw("status = 'terjual'")
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('lelang.show', compact('lelang', 'lainnya'));
    }
}
