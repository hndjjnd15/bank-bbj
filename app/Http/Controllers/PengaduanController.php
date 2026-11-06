<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Services\PengaduanNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
    // Tampilkan formulir: GET /pengaduan
    public function create()
    {
        return view('pengaduan', [
            'jenisList' => config('pengaduan.jenis'),
            'cabangList' => config('pengaduan.cabang'),
            'kategoriList' => config('pengaduan.kategori'),
        ]);
    }

    // Terima kiriman formulir: POST /pengaduan
    public function store(Request $request)
    {
        // Jebakan bot: kolom "website" tersembunyi, manusia tidak mengisinya
        if ($request->filled('website')) {
            return redirect('/pengaduan');
        }

        $data = $request->validate(
            [
                'jenis' => ['required', Rule::in(config('pengaduan.jenis'))],
                'nama' => ['required', 'string', 'min:3', 'max:100'],
                'nik' => ['required', 'digits:16'],
                'no_rekening' => ['nullable', 'regex:/^[0-9]{6,20}$/'],
                'telepon' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
                'email' => ['required', 'email', 'max:150'],
                'cabang' => ['required', Rule::in(config('pengaduan.cabang'))],
                'tanggal_kejadian' => ['nullable', 'date', 'before_or_equal:today'],
                'kategori' => ['required', Rule::in(config('pengaduan.kategori'))],
                'isi' => ['required', 'string', 'min:20', 'max:3000'],
                'lampiran' => [
                    'nullable', 'file',
                    'mimes:' . config('pengaduan.lampiran_mimes'),
                    'max:' . config('pengaduan.lampiran_maks_kb'),
                ],
                'persetujuan' => ['accepted'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'min' => ':attribute terlalu pendek (minimal :min karakter).',
                'max' => ':attribute terlalu panjang atau terlalu besar (maksimal :max).',
                'email' => 'Format email tidak valid.',
                'digits' => ':attribute harus berisi :digits digit angka.',
                'regex' => 'Format :attribute tidak valid (hanya angka).',
                'in' => 'Pilihan :attribute tidak valid.',
                'date' => 'Format :attribute tidak valid.',
                'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
                'mimes' => ':attribute harus berformat JPG, JPEG, PNG, atau PDF.',
                'accepted' => 'Anda harus menyetujui pernyataan ini untuk mengirim pengaduan.',
            ],
            [
                'jenis' => 'Jenis layanan',
                'nama' => 'Nama lengkap',
                'nik' => 'NIK',
                'no_rekening' => 'Nomor rekening',
                'telepon' => 'Nomor HP',
                'email' => 'Email',
                'cabang' => 'Cabang / unit layanan',
                'tanggal_kejadian' => 'Tanggal kejadian',
                'kategori' => 'Kategori',
                'isi' => 'Uraian',
                'lampiran' => 'Lampiran',
                'persetujuan' => 'Pernyataan persetujuan',
            ]
        );

        // Lampiran disimpan di storage PRIVAT (bukan folder publik)
        $lampiran = $request->file('lampiran')?->store('pengaduan', 'local');

        $pengaduan = Pengaduan::create(Arr::except($data, ['lampiran', 'persetujuan']) + [
            'kode' => Pengaduan::buatKode(),
            'status' => 'baru',
            'lampiran' => $lampiran,
        ]);

        // Kirim email + WhatsApp SETELAH halaman sukses ditampilkan, agar pengunjung tidak menunggu
        dispatch(function () use ($pengaduan) {
            app(PengaduanNotifier::class)->kirimSemua($pengaduan);
        })->afterResponse();

        return redirect('/pengaduan')->with('kode', $pengaduan->kode);
    }

    // Unduh lampiran (hanya untuk admin yang sudah login)
    public function lampiran(Pengaduan $pengaduan)
    {
        abort_unless($pengaduan->lampiran && Storage::disk('local')->exists($pengaduan->lampiran), 404);

        $ekstensi = pathinfo($pengaduan->lampiran, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($pengaduan->lampiran, $pengaduan->kode . '-lampiran.' . $ekstensi);
    }
}
