<?php

namespace App\Models;

use App\Filament\Resources\PengajuanKredits\PengajuanKreditResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PengajuanKredit extends Model
{
    public const STATUS = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
        'kode', 'nama_lengkap', 'nik', 'email', 'no_hp',
        'jenis_kredit', 'nama_produk', 'nominal_kredit',
        'status_pernikahan', 'domisili', 'alamat_domisili',
        'data', 'berkas', 'ip_address', 'user_agent',
        'status', 'catatan',
    ];

    protected $casts = [
        'nik' => 'encrypted',             // tersimpan terenkripsi di database
        'alamat_domisili' => 'encrypted',
        'data' => 'encrypted:array',      // seluruh isian formulir, terenkripsi
        'berkas' => 'array',
        'nominal_kredit' => 'integer',
        'email_terkirim_at' => 'datetime',
        'wa_terkirim_at' => 'datetime',
    ];

    // Nomor tiket unik, contoh: BBJ-20261118-3F7A2C
    // Bentuknya sama dengan formulir native: awalan-tanggal-6 karakter heksadesimal.
    public static function buatKode(): string
    {
        $awalan = config('pengajuan-kredit.kode_awalan', 'BBJ');
        $tanggal = now()->format('Ymd');

        do {
            $acak = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
            $kode = "{$awalan}-{$tanggal}-{$acak}";
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    public function getNominalRupiahAttribute(): string
    {
        return 'Rp '.number_format((float) $this->nominal_kredit, 0, ',', '.');
    }

    // Baris data pengajuan siap tampil (label => nilai) untuk email dan panel admin.
    // $samarkanNik dikendalikan melalui Pengaturan Situs di panel admin;
    // bawaannya NIK tampil lengkap agar cabang bisa memverifikasi.
    public function dataTampil(bool $samarkanNik = false): array
    {
        $data = $this->data ?? [];
        $label = config('pengajuan-kredit.label_data', []);
        $hasil = [];

        foreach ($data as $kunci => $nilai) {
            // Email dan jenis kredit sudah tampil di bagian kepala email/form admin
            if (in_array($kunci, ['email', 'jenis_kredit'], true)) {
                continue;
            }

            if (is_array($nilai) || blank($nilai)) {
                continue;
            }

            $judul = $label[$kunci] ?? ucwords(str_replace(['_', '-'], ' ', (string) $kunci));
            $hasil[$judul] = $this->formatNilai($kunci, (string) $nilai, $samarkanNik);
        }

        return $hasil;
    }

    private function formatNilai(string $kunci, string $nilai, bool $samarkanNik): string
    {
        if ($kunci === 'nik' && $samarkanNik) {
            return static::samarkan($nilai);
        }

        if ($kunci === 'nik') {
            return $nilai;
        }

        if ($kunci === 'domisili') {
            return config('pengajuan-kredit.domisili.'.$nilai, $nilai);
        }

        if ($kunci === 'status_pernikahan') {
            return config('pengajuan-kredit.status_pernikahan.'.$nilai, $nilai);
        }

        if ($kunci === 'nominal_kredit') {
            return 'Rp '.number_format((float) $nilai, 0, ',', '.');
        }

        if (str_starts_with($kunci, 'tanggal_') && filled($nilai)) {
            try {
                return Carbon::parse($nilai)->format('d-m-Y');
            } catch (\Throwable $e) {
                return $nilai;
            }
        }

        return $nilai;
    }

    // Daftar dokumen yang tersimpan, lengkap dengan label dan ukuran yang mudah dibaca.
    public function berkasTampil(): array
    {
        $label = config('pengajuan-kredit.berkas', []);
        $hasil = [];

        foreach ($this->berkas ?? [] as $kunci => $info) {
            if (blank($info['path'] ?? null)) {
                continue;
            }

            $ukuran = (int) ($info['ukuran'] ?? 0);

            $hasil[$kunci] = [
                'label' => $label[$kunci] ?? ucwords(str_replace('_', ' ', (string) $kunci)),
                'nama' => $info['nama'] ?? basename($info['path']),
                'ukuran' => $ukuran > 0 ? number_format($ukuran / 1024, 0, ',', '.').' KB' : '-',
                'path' => $info['path'],
            ];
        }

        return $hasil;
    }

    // Tampilkan hanya 4 digit terakhir, contoh: ************3456
    public static function samarkan(?string $nilai): string
    {
        if (blank($nilai)) {
            return '-';
        }

        return str_repeat('*', max(strlen($nilai) - 4, 0)).substr($nilai, -4);
    }

    // Alamat halaman tindak lanjut di panel admin
    public function urlAdmin(): string
    {
        try {
            return PengajuanKreditResource::getUrl('edit', ['record' => $this]);
        } catch (\Throwable $e) {
            return url('/admin');
        }
    }
}
