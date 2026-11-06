<?php

namespace App\Services;

use App\Mail\LaporanWbsDiterima;
use App\Mail\LaporanWbsMasuk;
use App\Models\LaporanPelanggaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class WbsNotifier
{
    // Dipanggil setelah laporan tersimpan. Kegagalan di sini TIDAK membatalkan laporan,
    // hanya dicatat di storage/logs/laravel.log.
    public function kirimSemua(LaporanPelanggaran $l): void
    {
        $this->kirimEmail($l);        // ke tim WBS
        $this->kirimBalasan($l);      // tanda terima ke pelapor
        $this->kirimWhatsApp($l);
    }

    public function kirimEmail(LaporanPelanggaran $l): void
    {
        $tujuan = config('wbs.email_tujuan');
        if (blank($tujuan)) {
            Log::warning('Email WBS dilewati: WBS_EMAIL_TUJUAN belum diisi di .env.', ['kode' => $l->kode]);

            return;
        }

        try {
            Mail::to($tujuan)->send(new LaporanWbsMasuk($l));
            $l->forceFill(['email_terkirim_at' => now()])->save();
        } catch (Throwable $e) {
            Log::error('Email WBS gagal dikirim: ' . $e->getMessage(), ['kode' => $l->kode]);
        }
    }

    // Tanda terima untuk pelapor. Isinya sengaja minim: hanya kode, tanpa kata "WBS", kategori, uraian, atau nama.
    public function kirimBalasan(LaporanPelanggaran $l): void
    {
        if (! config('wbs.balas_pelapor') || blank($l->email)) {
            return;
        }

        try {
            Mail::to($l->email)->send(new LaporanWbsDiterima($l));
            Log::info('Email tanda terima WBS ke pelapor terkirim.', ['kode' => $l->kode]);
        } catch (Throwable $e) {
            Log::error('Email tanda terima WBS ke pelapor gagal: ' . $e->getMessage(), ['kode' => $l->kode]);
        }
    }

    public function kirimWhatsApp(LaporanPelanggaran $l): void
    {
        $hasil = app(WhatsAppSender::class)->kirim(config('wbs.wa'), $this->pesan($l));

        if ($hasil['ok']) {
            $l->forceFill(['wa_terkirim_at' => now()])->save();
            Log::info('WhatsApp WBS terkirim. ' . $hasil['ringkas'], ['kode' => $l->kode]);
        } else {
            Log::warning('WhatsApp WBS GAGAL. ' . $hasil['ringkas'], ['kode' => $l->kode]);
        }
    }

    // Pesan WhatsApp sengaja TIDAK memuat identitas pelapor maupun isi laporan.
    // WhatsApp bukan kanal yang aman untuk data itu; tim membukanya lewat panel admin.
    public function pesan(LaporanPelanggaran $l): string
    {
        return implode("\n", [
            '*LAPORAN WBS BARU*',
            '',
            "Kode: {$l->kode}",
            "Kategori: {$l->kategori}",
            "Hubungan pelapor: {$l->hubungan}",
            'Lokasi / unit: ' . ($l->lokasi ?: '-'),
            'Tanggal kejadian: ' . ($l->tanggal_kejadian?->format('d-m-Y') ?? '-'),
            'Lampiran: ' . ($l->lampiran ? 'Ada' : 'Tidak ada'),
            '',
            'Isi laporan dan identitas pelapor tidak dikirim lewat WhatsApp. Buka panel admin untuk menindaklanjuti:',
            $l->urlAdmin(),
        ]);
    }
}
