<?php

namespace App\Mail;

use App\Models\PengajuanKredit;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PengajuanKreditDiterima extends Mailable
{
    public function __construct(public PengajuanKredit $pengajuan) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Konfirmasi Pengajuan Kredit Bank BBJ - {$this->pengajuan->kode}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan-kredit-diterima',
        );
    }
}
