<?php

namespace App\Mail;

use App\Models\LaporanPelanggaran;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class LaporanWbsMasuk extends Mailable
{
    public function __construct(public LaporanPelanggaran $laporan)
    {
    }

    public function envelope(): Envelope
    {
        // Alamat "Balas" ke pelapor hanya dipasang jika identitas memang boleh disertakan
        $balas = (config('wbs.sertakan_identitas') && $this->laporan->email)
            ? [new Address($this->laporan->email, $this->laporan->nama_pelapor ?: 'Pelapor')]
            : [];

        return new Envelope(
            subject: "[WBS] {$this->laporan->kode} - {$this->laporan->kategori}",
            replyTo: $balas,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wbs-masuk',
            with: [
                'urlAdmin' => $this->laporan->urlAdmin(),
                'sertakanIdentitas' => (bool) config('wbs.sertakan_identitas'),
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->laporan->lampiran) {
            return [];
        }

        return [Attachment::fromStorageDisk('local', $this->laporan->lampiran)];
    }
}
