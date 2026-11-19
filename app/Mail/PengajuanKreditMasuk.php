<?php

namespace App\Mail;

use App\Models\PengajuanKredit;
use App\Models\PengaturanSitus;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PengajuanKreditMasuk extends Mailable
{
    /**
     * $denganLampiran = false dipakai sebagai cadangan saat email berlampiran
     * ditolak server (misalnya melebihi batas ukuran), supaya notifikasi data
     * pengajuan tetap sampai ke tim kredit.
     */
    public function __construct(
        public PengajuanKredit $pengajuan,
        public bool $denganLampiran = true,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[PENGAJUAN KREDIT] {$this->pengajuan->kode} - {$this->pengajuan->nama_produk}",
            // Saat staf menekan "Balas", email langsung ke pemohon
            replyTo: $this->pengajuan->email
                ? [new Address($this->pengajuan->email, $this->pengajuan->nama_lengkap)]
                : [],
        );
    }

    public function content(): Content
    {
        // Tombol ke panel admin hanya ditampilkan kalau diaktifkan lewat
        // PENGAJUAN_KREDIT_LINK_ADMIN. Data dan dokumen sudah ada di email ini.
        return new Content(
            view: 'emails.pengajuan-kredit-masuk',
            with: [
                'urlAdmin' => config('pengajuan-kredit.link_admin')
                    ? $this->pengajuan->urlAdmin()
                    : null,
                'denganLampiran' => $this->denganLampiran,
                'samarkanNik' => (bool) (PengaturanSitus::saatIni()?->pengajuan_kredit_samarkan_nik ?? false),
            ],
        );
    }

    // Semua dokumen pengajuan dilampirkan dengan nama aslinya
    public function attachments(): array
    {
        if (! $this->denganLampiran) {
            return [];
        }

        $lampiran = [];

        foreach ($this->pengajuan->berkas ?? [] as $info) {
            if (blank($info['path'] ?? null)) {
                continue;
            }

            $attachment = Attachment::fromStorageDisk('local', $info['path'])
                ->as($info['nama'] ?? basename($info['path']));

            if (filled($info['mime'] ?? null)) {
                $attachment->withMime($info['mime']);
            }

            $lampiran[] = $attachment;
        }

        return $lampiran;
    }
}
