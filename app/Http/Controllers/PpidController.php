<?php

namespace App\Http\Controllers;

use App\Models\DokumenPpid;
use App\Models\PermohonanInformasi;
use App\Services\PpidNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PpidController extends Controller
{
    // Halaman utama: /ppid
    public function index()
    {
        $jumlah = DokumenPpid::query()
            ->where('diterbitkan', true)
            ->selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('ppid.index', ['jumlah' => $jumlah]);
    }

    // Daftar informasi publik: /ppid/informasi  (contoh: ?kategori=berkala&tahun=2026&q=laporan)
    public function informasi(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = $request->query('kategori');
        $tahun = $request->query('tahun');

        $tahunList = DokumenPpid::query()->where('diterbitkan', true)->distinct()->orderByDesc('tahun')->pluck('tahun')->all();

        // Nilai yang tidak dikenal diabaikan (bukan error)
        $kategori = is_string($kategori) && array_key_exists($kategori, config('ppid.kategori')) ? $kategori : null;
        $tahun = is_numeric($tahun) && in_array((int) $tahun, $tahunList, true) ? (int) $tahun : null;

        $dokumens = DokumenPpid::query()
            ->where('diterbitkan', true)
            ->when($q !== '', function ($query) use ($q) {
                $cari = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(fn ($w) => $w->where('judul', 'like', $cari)->orWhere('deskripsi', 'like', $cari));
            })
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->when($tahun, fn ($query) => $query->where('tahun', $tahun))
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('ppid.informasi', [
            'dokumens' => $dokumens,
            'tahunList' => $tahunList,
            'filter' => compact('q', 'kategori', 'tahun'),
            'adaFilter' => $q !== '' || $kategori || $tahun,
        ]);
    }

    // Formulir permohonan: GET /ppid/permohonan
    public function permohonan()
    {
        return view('ppid.permohonan', [
            'jenisList' => config('ppid.jenis_pemohon'),
            'caraList' => config('ppid.cara_memperoleh'),
        ]);
    }

    // Terima permohonan: POST /ppid/permohonan
    public function kirimPermohonan(Request $request)
    {
        // Jebakan bot: kolom "website" tersembunyi, manusia tidak mengisinya
        if ($request->filled('website')) {
            return redirect('/ppid/permohonan');
        }

        $data = $request->validate(
            [
                'jenis_pemohon' => ['required', Rule::in(config('ppid.jenis_pemohon'))],
                'nama' => ['required', 'string', 'min:3', 'max:100'],
                'nomor_identitas' => ['required', 'regex:/^[0-9]{15,20}$/'],
                'alamat' => ['required', 'string', 'min:10', 'max:300'],
                'pekerjaan' => ['nullable', 'string', 'max:100'],
                'telepon' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
                'email' => ['required', 'email', 'max:150'],
                'rincian' => ['required', 'string', 'min:20', 'max:2000'],
                'tujuan' => ['required', 'string', 'min:10', 'max:1000'],
                'cara_memperoleh' => ['required', Rule::in(config('ppid.cara_memperoleh'))],
                'persetujuan' => ['accepted'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'min' => ':attribute terlalu pendek (minimal :min karakter).',
                'max' => ':attribute terlalu panjang (maksimal :max karakter).',
                'email' => 'Format email tidak valid.',
                'regex' => 'Format :attribute tidak valid (hanya angka).',
                'in' => 'Pilihan :attribute tidak valid.',
                'accepted' => 'Anda harus menyetujui pernyataan ini untuk mengirim permohonan.',
            ],
            [
                'jenis_pemohon' => 'Jenis pemohon',
                'nama' => 'Nama lengkap',
                'nomor_identitas' => 'Nomor identitas (KTP/NPWP)',
                'alamat' => 'Alamat',
                'pekerjaan' => 'Pekerjaan',
                'telepon' => 'Nomor HP',
                'email' => 'Email',
                'rincian' => 'Rincian informasi',
                'tujuan' => 'Tujuan penggunaan informasi',
                'cara_memperoleh' => 'Cara memperoleh informasi',
                'persetujuan' => 'Pernyataan persetujuan',
            ]
        );

        $permohonan = PermohonanInformasi::create(collect($data)->except('persetujuan')->all() + [
            'kode' => PermohonanInformasi::buatKode(),
            'status' => 'baru',
        ]);

        // Kirim email + WhatsApp SETELAH halaman sukses tampil, agar pemohon tidak menunggu
        dispatch(function () use ($permohonan) {
            app(PpidNotifier::class)->kirimSemua($permohonan);
        })->afterResponse();

        return redirect('/ppid/permohonan')->with('kode_ppid', $permohonan->kode);
    }
}
