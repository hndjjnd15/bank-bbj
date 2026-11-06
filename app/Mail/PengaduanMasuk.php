<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PengaduanMasuk extends Mailable
{
    public function __construct(public Pengaduan $pengaduan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->pengaduan->jenis}] {$this->pengaduan->kode} - {$this->pengaduan->nama}",
            // Saat staf menekan "Balas", email langsung ke nasabah
            replyTo: $this->pengaduan->email
                ? [new Address($this->pengaduan->email, $this->pengaduan->nama)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-masuk',
            with: ['urlAdmin' => $this->pengaduan->urlAdmin()],
        );
    }

    public function attachments(): array
    {
        if (! $this->pengaduan->lampiran) {
            return [];
        }

        return [Attachment::fromStorageDisk('local', $this->pengaduan->lampiran)];
    }
}
