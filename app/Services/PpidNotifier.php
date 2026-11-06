<?php

namespace App\Services;

use App\Mail\PermohonanInformasiDiterima;
use App\Mail\PermohonanInformasiMasuk;
use App\Models\PermohonanInformasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PpidNotifier
{
    // Dipanggil setelah permohonan tersimpan. Kegagalan di sini TIDAK membatalkan permohonan,
    // hanya dicatat di storage/logs/laravel.log.
    public function kirimSemua(PermohonanInformasi $p): void
    {
        $this->kirimEmail($p);      // ke petugas PPID
        $this->kirimBalasan($p);    // tanda terima ke pemohon
        $this->kirimWhatsApp($p);
    }

    public function kirimEmail(PermohonanInformasi $p): void
    {
        $tujuan = config('ppid.email_tujuan');
        if (blank($tujuan)) {
            Log::warning('Email PPID dilewati: PPID_EMAIL_TUJUAN belum diisi di .env.', ['kode' => $p->kode]);

            return;
        }

        try {
            Mail::to($tujuan)->send(new PermohonanInformasiMasuk($p));
            $p->forceFill(['email_terkirim_at' => now()])->save();
        } catch (Throwable $e) {
            Log::error('Email PPID gagal dikirim: ' . $e->getMessage(), ['kode' => $p->kode]);
        }
    }

    // Tanda terima untuk pemohon: nomor registrasi dan ketentuan waktu, tanpa isi permohonan.
    public function kirimBalasan(PermohonanInformasi $p): void
    {
        if (! config('ppid.balas_pemohon') || blank($p->email)) {
            return;
        }

        try {
            Mail::to($p->email)->send(new PermohonanInformasiDiterima($p));
            Log::info('Email tanda terima PPID ke pemohon terkirim.', ['kode' => $p->kode]);
        } catch (Throwable $e) {
            Log::error('Email tanda terima PPID ke pemohon gagal: ' . $e->getMessage(), ['kode' => $p->kode]);
        }
    }

    public function kirimWhatsApp(PermohonanInformasi $p): void
    {
        $hasil = app(WhatsAppSender::class)->kirim(config('ppid.wa'), $this->pesan($p));

        if ($hasil['ok']) {
            $p->forceFill(['wa_terkirim_at' => now()])->save();
            Log::info('WhatsApp PPID terkirim. ' . $hasil['ringkas'], ['kode' => $p->kode]);
        } else {
            Log::warning('WhatsApp PPID GAGAL. ' . $hasil['ringkas'], ['kode' => $p->kode]);
        }
    }

    // Nomor identitas dan alamat pemohon tidak disertakan di WhatsApp; lihat di panel admin.
    public function pesan(PermohonanInformasi $p): string
    {
        return implode("\n", [
            '*PERMOHONAN INFORMASI PUBLIK BARU*',
            '',
            "No. registrasi: {$p->kode}",
            "Pemohon: {$p->nama} ({$p->jenis_pemohon})",
            "No. HP: {$p->telepon}",
            "Cara memperoleh: {$p->cara_memperoleh}",
            'Batas tanggapan: ' . $p->batas_tanggapan->format('d-m-Y'),
            '',
            '*Informasi yang diminta:*',
            mb_strimwidth($p->rincian, 0, 300, '...'),
            '',
            'Tindak lanjut: ' . $p->urlAdmin(),
        ]);
    }
}
