<?php

namespace App\Mail;

use App\Models\PermohonanInformasi;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Email ke petugas PPID
class PermohonanInformasiMasuk extends Mailable
{
    public function __construct(public PermohonanInformasi $permohonan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[PPID] {$this->permohonan->kode} - {$this->permohonan->nama}",
            // Saat petugas menekan "Balas", email langsung ke pemohon
            replyTo: [new Address($this->permohonan->email, $this->permohonan->nama)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppid-masuk',
            with: ['urlAdmin' => $this->permohonan->urlAdmin()],
        );
    }
}
