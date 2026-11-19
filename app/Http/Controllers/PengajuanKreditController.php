<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanKreditRequest;
use App\Models\PengajuanKredit;
use App\Services\PengajuanKreditNotifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class PengajuanKreditController extends Controller
{
    // Kolom teks yang disimpan sebagai JSON "data" (urutan mengikuti formulir)
    private const KOLOM_TEKS = [
        'nama_lengkap', 'nik', 'jenis_kelamin', 'agama', 'tempat_lahir', 'tanggal_lahir',
        'alamat_ktp', 'domisili', 'alamat_domisili', 'npwp', 'no_hp',
        'nama_usaha', 'lokasi_usaha',
        'instansi', 'jabatan',
        'status_pernikahan',
        'nama_pasangan', 'hubungan_pasangan', 'nik_pasangan', 'agama_pasangan',
        'tempat_lahir_pasangan', 'tanggal_lahir_pasangan', 'npwp_pasangan', 'hp_pasangan',
        'nama_orang_tua', 'hubungan_orang_tua', 'nik_orang_tua', 'agama_orang_tua',
        'tempat_lahir_orang_tua', 'tanggal_lahir_orang_tua', 'npwp_orang_tua', 'hp_orang_tua',
    ];

    // Tampilkan formulir: GET /pengajuan-kredit
    public function create()
    {
        return view('pengajuan-kredit.index', [
            'produkList' => config('pengajuan-kredit.produk'),
            'agamaList' => config('pengajuan-kredit.agama'),
            'jenisKelaminList' => config('pengajuan-kredit.jenis_kelamin'),
            'statusList' => config('pengajuan-kredit.status_pernikahan'),
            'domisiliList' => config('pengajuan-kredit.domisili'),
            'produkUsaha' => config('pengajuan-kredit.produk_usaha'),
            'produkPekerjaan' => config('pengajuan-kredit.produk_pekerjaan'),
        ]);
    }

    // Terima kiriman formulir: POST /pengajuan-kredit
    public function store(PengajuanKreditRequest $request)
    {
        // Jebakan bot: kolom "website" tersembunyi, manusia tidak mengisinya
        if ($request->filled('website')) {
            return redirect()->route('pengajuan-kredit.create');
        }

        $data = $request->validated();

        $kode = PengajuanKredit::buatKode();

        // Dokumen disimpan di storage PRIVAT (bukan folder publik),
        // satu folder per nomor tiket.
        $berkas = [];
        foreach (array_keys(config('pengajuan-kredit.berkas', [])) as $kunci) {
            if (! $request->hasFile($kunci)) {
                continue;
            }

            $file = $request->file($kunci);
            $path = $file->store("pengajuan-kredit/{$kode}", 'local');

            $berkas[$kunci] = [
                'path' => $path,
                'nama' => $file->getClientOriginalName(),
                'ukuran' => $file->getSize(),
                'mime' => $file->getClientMimeType(),
            ];
        }

        // Simpan seluruh isian teks apa adanya (bagian yang tidak aktif sudah
        // dibuang oleh aturan exclude_unless pada FormRequest).
        $teks = array_filter(
            Arr::only($data, self::KOLOM_TEKS),
            fn ($nilai) => filled($nilai)
        );

        $pengajuan = PengajuanKredit::create([
            'kode' => $kode,
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
            'jenis_kredit' => $data['jenis_kredit'],
            'nama_produk' => config('pengajuan-kredit.produk.'.$data['jenis_kredit'], $data['jenis_kredit']),
            'nominal_kredit' => (int) $data['nominal_kredit'],
            'status_pernikahan' => $data['status_pernikahan'],
            'domisili' => $data['domisili'],
            'alamat_domisili' => $data['alamat_domisili'] ?? null,
            'data' => $teks,
            'berkas' => $berkas ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            'status' => 'baru',
        ]);

        // Kirim email + WhatsApp SETELAH halaman sukses ditampilkan,
        // agar pengunjung tidak menunggu proses SMTP.
        dispatch(function () use ($pengajuan) {
            app(PengajuanKreditNotifier::class)->kirimSemua($pengajuan);
        })->afterResponse();

        return redirect()->route('pengajuan-kredit.sukses', $pengajuan->kode);
    }

    // Halaman sukses: GET /pengajuan-kredit/sukses/{tiket}
    public function sukses(string $tiket)
    {
        $pengajuan = PengajuanKredit::where('kode', $tiket)->firstOrFail();

        return view('pengajuan-kredit.sukses', ['pengajuan' => $pengajuan]);
    }

    // Unduh dokumen pengajuan (hanya untuk admin yang sudah login)
    public function berkas(PengajuanKredit $pengajuan, string $kunci)
    {
        $info = ($pengajuan->berkas ?? [])[$kunci] ?? null;

        abort_unless($info && Storage::disk('local')->exists($info['path']), 404);

        $nama = $info['nama'] ?? basename($info['path']);

        return Storage::disk('local')->download($info['path'], $pengajuan->kode.'-'.$nama);
    }

    // Unduh semua dokumen sekaligus sebagai satu file ZIP
    public function berkasZip(PengajuanKredit $pengajuan)
    {
        $daftar = collect($pengajuan->berkas ?? [])
            ->filter(fn ($info) => filled($info['path'] ?? null) && Storage::disk('local')->exists($info['path']));

        abort_if($daftar->isEmpty(), 404);

        $namaZip = $pengajuan->kode.'-dokumen.zip';
        $tujuan = storage_path('app/'.$namaZip);

        $zip = new \ZipArchive;

        if ($zip->open($tujuan, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Gagal membuat file ZIP.');
        }

        foreach ($daftar as $kunci => $info) {
            $namaDiZip = $kunci.'-'.($info['nama'] ?? basename($info['path']));
            $zip->addFile(Storage::disk('local')->path($info['path']), $namaDiZip);
        }

        $zip->close();

        return response()->download($tujuan)->deleteFileAfterSend(true);
    }
}
