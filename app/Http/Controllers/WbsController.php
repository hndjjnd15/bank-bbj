<?php

namespace App\Http\Controllers;

use App\Models\LaporanPelanggaran;
use App\Services\WbsNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WbsController extends Controller
{
    // Tampilkan halaman dan formulir: GET /wbs
    public function create()
    {
        return view('wbs.index', [
            'hubunganList' => config('wbs.hubungan'),
            'kategoriList' => config('wbs.kategori'),
        ]);
    }

    // Terima laporan: POST /wbs
    public function store(Request $request)
    {
        // Jebakan bot: kolom "website" tersembunyi, manusia tidak mengisinya
        if ($request->filled('website')) {
            return redirect('/wbs');
        }

        $data = $request->validate(
            [
                'nama_pelapor' => ['nullable', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150'],
                'telepon' => ['nullable', 'regex:/^[0-9+\-\s]{8,20}$/'],
                'hubungan' => ['required', Rule::in(config('wbs.hubungan'))],
                'kategori' => ['required', Rule::in(config('wbs.kategori'))],
                'tanggal_kejadian' => ['nullable', 'date', 'before_or_equal:today'],
                'lokasi' => ['nullable', 'string', 'max:150'],
                'uraian' => ['required', 'string', 'min:30', 'max:5000'],
                'lampiran' => [
                    'nullable', 'file',
                    'mimes:' . config('wbs.lampiran_mimes'),
                    'max:' . config('wbs.lampiran_maks_kb'),
                ],
                'persetujuan' => ['accepted'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'min' => ':attribute terlalu pendek (minimal :min karakter).',
                'max' => ':attribute terlalu panjang atau terlalu besar (maksimal :max).',
                'email' => 'Format email tidak valid.',
                'regex' => 'Format :attribute tidak valid (hanya angka).',
                'in' => 'Pilihan :attribute tidak valid.',
                'date' => 'Format :attribute tidak valid.',
                'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
                'mimes' => ':attribute harus berformat JPG, JPEG, PNG, atau PDF.',
                'accepted' => 'Anda harus menyetujui pernyataan ini untuk mengirim laporan.',
            ],
            [
                'nama_pelapor' => 'Nama pelapor',
                'email' => 'Email',
                'telepon' => 'Nomor telepon',
                'hubungan' => 'Hubungan dengan bank',
                'kategori' => 'Kategori laporan',
                'tanggal_kejadian' => 'Tanggal kejadian',
                'lokasi' => 'Lokasi / unit kerja',
                'uraian' => 'Uraian laporan',
                'lampiran' => 'Lampiran',
                'persetujuan' => 'Pernyataan persetujuan',
            ]
        );

        // Lampiran disimpan di storage PRIVAT (bukan folder publik)
        $lampiran = $request->file('lampiran')?->store('wbs', 'local');

        $laporan = LaporanPelanggaran::create(Arr::except($data, ['lampiran', 'persetujuan']) + [
            'kode' => LaporanPelanggaran::buatKode(),
            'status' => 'baru',
            'lampiran' => $lampiran,
        ]);

        // Kirim email + WhatsApp SETELAH halaman sukses tampil, agar pelapor tidak menunggu
        dispatch(function () use ($laporan) {
            app(WbsNotifier::class)->kirimSemua($laporan);
        })->afterResponse();

        return redirect('/wbs')->with('kode_wbs', $laporan->kode);
    }

    // Unduh lampiran (hanya untuk admin yang sudah login)
    public function lampiran(LaporanPelanggaran $laporan)
    {
        abort_unless($laporan->lampiran && Storage::disk('local')->exists($laporan->lampiran), 404);

        $ekstensi = pathinfo($laporan->lampiran, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($laporan->lampiran, $laporan->kode . '-lampiran.' . $ekstensi);
    }
}
