<?php

namespace App\Services;

use App\Mail\PengajuanKreditDiterima;
use App\Mail\PengajuanKreditMasuk;
use App\Models\PengajuanKredit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PengajuanKreditNotifier
{
    // Dipanggil setelah pengajuan tersimpan. Kegagalan di sini TIDAK membatalkan
    // pengajuan karena data sudah tersimpan di database; hanya dicatat di log.
    public function kirimSemua(PengajuanKredit $p): void
    {
        $this->kirimEmail($p);        // ke email corporate (tim kredit)
        $this->kirimBalasan($p);      // email konfirmasi ke pemohon
        $this->kirimWhatsApp($p);
    }

    public function kirimEmail(PengajuanKredit $p): void
    {
        $tujuan = config('pengajuan-kredit.email_tujuan');

        if (blank($tujuan)) {
            Log::warning('Email pengajuan kredit dilewati: PENGAJUAN_KREDIT_EMAIL_TUJUAN belum diisi.', ['kode' => $p->kode]);

            return;
        }

        try {
            Mail::to($tujuan)->send(new PengajuanKreditMasuk($p));
            $p->forceFill(['email_terkirim_at' => now()])->save();

            return;
        } catch (Throwable $e) {
            // Server email bisa menolak pesan karena total lampiran terlalu besar.
            // Dicoba sekali lagi tanpa lampiran supaya data pengajuan tetap masuk.
            Log::warning('Email pengajuan kredit dengan lampiran gagal, mencoba ulang tanpa lampiran: '.$e->getMessage(), ['kode' => $p->kode]);
        }

        try {
            Mail::to($tujuan)->send(new PengajuanKreditMasuk($p, denganLampiran: false));
            $p->forceFill(['email_terkirim_at' => now()])->save();
            Log::warning('Email pengajuan kredit terkirim TANPA lampiran; dokumen diunduh lewat panel admin.', ['kode' => $p->kode]);
        } catch (Throwable $e) {
            Log::error('Email pengajuan kredit gagal dikirim: '.$e->getMessage(), ['kode' => $p->kode]);
        }
    }

    public function kirimBalasan(PengajuanKredit $p): void
    {
        if (blank($p->email)) {
            return;
        }

        try {
            Mail::to($p->email)->send(new PengajuanKreditDiterima($p));
            Log::info('Email konfirmasi pengajuan kredit terkirim ke pemohon.', ['kode' => $p->kode]);
        } catch (Throwable $e) {
            Log::error('Email konfirmasi pengajuan kredit gagal: '.$e->getMessage(), ['kode' => $p->kode]);
        }
    }

    public function kirimWhatsApp(PengajuanKredit $p): void
    {
        $wa = config('pengajuan-kredit.wa');

        // Nomor berawalan 0 (mis. 0838...) disesuaikan ke format internasional 62.
        $wa['tujuan'] = array_values(array_filter(array_map(
            fn ($nomor) => $this->normalkanNomor((string) $nomor),
            $wa['tujuan'] ?? []
        )));

        $hasil = app(WhatsAppSender::class)->kirim($wa, $this->pesan($p));

        if ($hasil['ok']) {
            $p->forceFill(['wa_terkirim_at' => now()])->save();
            Log::info('WhatsApp pengajuan kredit terkirim. '.$hasil['ringkas'], ['kode' => $p->kode]);
        } else {
            Log::warning('WhatsApp pengajuan kredit GAGAL. '.$hasil['ringkas'], ['kode' => $p->kode]);
        }
    }

    private function normalkanNomor(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor) ?? '';

        if ($nomor === '') {
            return '';
        }

        if (str_starts_with($nomor, '0')) {
            return '62'.substr($nomor, 1);
        }

        if (str_starts_with($nomor, '8')) {
            return '62'.$nomor;
        }

        return $nomor;
    }

    // Isi pesan WhatsApp mengikuti formulir native, ditambah tautan ke email kredit.
    public function pesan(PengajuanKredit $p): string
    {
        $emailTujuan = (string) config('pengajuan-kredit.email_tujuan', '');
        $urlEmail = (string) config('pengajuan-kredit.url_email', '');

        $baris = [
            '*PENGAJUAN KREDIT BARU - BANK BBJ*',
            '',
            "Nomor Tiket : *{$p->kode}*",
            "Nama        : *{$p->nama_lengkap}*",
            "Produk      : *{$p->nama_produk}*",
            "Nominal     : *{$p->nominal_rupiah}*",
            "Email       : {$p->email}",
            "No. HP      : {$p->no_hp}",
            '',
            'Pengajuan baru telah masuk melalui Formulir Pengajuan Kredit Bank BBJ.',
            '',
        ];

        // Tautan "buka email" hanya ditambahkan kalau alamatnya sudah diatur.
        if (filled($emailTujuan) && filled($urlEmail)) {
            $baris[] = 'Data lengkap dan seluruh dokumen sudah dikirim ke email:';
            $baris[] = $emailTujuan;
            $baris[] = "Buka email: {$urlEmail}";
            $baris[] = '';
        } elseif (filled($emailTujuan)) {
            $baris[] = "Data lengkap dan seluruh dokumen sudah dikirim ke email: {$emailTujuan}";
            $baris[] = '';
        }

        $baris[] = 'Bank BBJ Amanah Kuat Peduli';

        return implode("\n", $baris);
    }
}
