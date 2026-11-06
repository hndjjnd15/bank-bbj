<?php

namespace App\Mail;

use App\Models\PermohonanInformasi;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Tanda terima untuk pemohon
class PermohonanInformasiDiterima extends Mailable
{
    public function __construct(public PermohonanInformasi $permohonan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permohonan informasi Anda telah kami terima (' . $this->permohonan->kode . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppid-diterima',
            with: [
                'bank' => config('pengaduan.nama_bank'),
                'tenggat' => config('ppid.tenggat_hari_kerja'),
                'perpanjangan' => config('ppid.perpanjangan_hari_kerja'),
                'kontakEmail' => config('ppid.kontak.email'),
            ],
        );
    }
}
