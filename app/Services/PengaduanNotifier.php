<?php

namespace App\Services;

use App\Mail\PengaduanDiterima;
use App\Mail\PengaduanMasuk;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class PengaduanNotifier
{
    // Dipanggil setelah pengaduan tersimpan. Kegagalan di sini TIDAK membatalkan pengaduan,
    // hanya dicatat di storage/logs/laravel.log.
    public function kirimSemua(Pengaduan $p): void
    {
        $this->kirimEmail($p);        // ke email corporate (staf)
        $this->kirimBalasan($p);      // balasan otomatis ke nasabah
        $this->kirimWhatsApp($p);
    }

    public function kirimEmail(Pengaduan $p): void
    {
        $tujuan = config('pengaduan.email_tujuan');
        if (blank($tujuan)) {
            return;
        }

        try {
            Mail::to($tujuan)->send(new PengaduanMasuk($p));
            $p->forceFill(['email_terkirim_at' => now()])->save();
        } catch (Throwable $e) {
            Log::error('Email pengaduan gagal dikirim: ' . $e->getMessage(), ['kode' => $p->kode]);
        }
    }

    // Email tanda terima untuk nasabah. Sengaja TIDAK memuat NIK, rekening, maupun isi uraian,
    // supaya tidak membocorkan data jika ada yang mengisi form dengan email orang lain.
    public function kirimBalasan(Pengaduan $p): void
    {
        if (blank($p->email)) {
            return;
        }

        try {
            Mail::to($p->email)->send(new PengaduanDiterima($p));
            Log::info('Email balasan ke nasabah terkirim.', ['kode' => $p->kode]);
        } catch (Throwable $e) {
            Log::error('Email balasan ke nasabah gagal: ' . $e->getMessage(), ['kode' => $p->kode]);
        }
    }

    public function kirimWhatsApp(Pengaduan $p): void
    {
        $hasil = $this->kirimPesan($this->pesanWhatsApp($p));

        if ($hasil['ok']) {
            $p->forceFill(['wa_terkirim_at' => now()])->save();
            Log::info('WhatsApp pengaduan terkirim. ' . $hasil['ringkas'], ['kode' => $p->kode]);
        } else {
            Log::warning('WhatsApp pengaduan GAGAL. ' . $hasil['ringkas'], ['kode' => $p->kode]);
        }
    }

    // Mengirim satu pesan lewat WASender dan mengembalikan hasilnya (dipakai juga oleh perintah tes).
    // Format WASender: POST JSON { api_key, sender, number, message }; beberapa penerima dipisah "|".
    public function kirimPesan(string $pesan): array
    {
        $wa = config('pengaduan.wa');

        $kurang = [];
        if (blank($wa['api_key'])) {
            $kurang[] = 'WASENDER_API_KEY';
        }
        if (blank($wa['sender'])) {
            $kurang[] = 'WASENDER_SENDER';
        }
        if (empty($wa['tujuan'])) {
            $kurang[] = 'WASENDER_TUJUAN';
        }
        if ($kurang) {
            return ['ok' => false, 'ringkas' => 'Belum diisi di .env: ' . implode(', ', $kurang)];
        }

        try {
            $respons = Http::asJson()->timeout(15)->post($wa['url'], [
                'api_key' => $wa['api_key'],
                'sender' => $wa['sender'],
                'number' => implode('|', $wa['tujuan']),
                'message' => $pesan,
            ]);
        } catch (Throwable $e) {
            return ['ok' => false, 'ringkas' => 'Tidak bisa terhubung ke WASender: ' . $e->getMessage()];
        }

        // Sebagian API membalas HTTP 200 tetapi berisi status gagal di dalam JSON
        $json = $respons->json();
        $statusGagal = is_array($json)
            && array_key_exists('status', $json)
            && in_array($json['status'], [false, 'false', 0, '0', 'error', 'failed'], true);

        return [
            'ok' => $respons->successful() && ! $statusGagal,
            'ringkas' => 'HTTP ' . $respons->status() . ' | Balasan: ' . Str::limit($respons->body(), 300),
        ];
    }

    // Isi pesan WhatsApp. NIK dan nomor rekening sengaja tidak disertakan
    // (WhatsApp bukan kanal yang aman untuk data itu); lihat lengkapnya di panel admin.
    private function pesanWhatsApp(Pengaduan $p): string
    {
        $judul = $p->jenis === 'Pengaduan' ? 'PENGADUAN BARU' : 'SARAN & MASUKAN BARU';
        $uraian = mb_strimwidth($p->isi, 0, 500, '...');

        return implode("\n", [
            "*{$judul}*",
            '',
            "Kode tiket: {$p->kode}",
            "Nama: {$p->nama}",
            "No. HP: {$p->telepon}",
            'Cabang: ' . ($p->cabang ?: '-'),
            'Kategori: ' . ($p->kategori ?: '-'),
            'Tanggal kejadian: ' . ($p->tanggal_kejadian?->format('d-m-Y') ?? '-'),
            'Lampiran: ' . ($p->lampiran ? 'Ada' : 'Tidak ada'),
            '',
            '*Uraian:*',
            $uraian,
            '',
            'Tindak lanjut: ' . $p->urlAdmin(),
        ]);
    }
}
