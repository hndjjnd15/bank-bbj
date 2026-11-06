<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Email balasan otomatis yang dikirim ke NASABAH sebagai tanda terima.
class PengaduanDiterima extends Mailable
{
    public function __construct(public Pengaduan $pengaduan)
    {
    }

    public function envelope(): Envelope
    {
        $tujuanBalas = config('pengaduan.email_tujuan');

        return new Envelope(
            subject: "{$this->pengaduan->jenis} Anda telah kami terima ({$this->pengaduan->kode})",
            // Jika nasabah menekan "Balas", emailnya masuk ke email corporate
            replyTo: $tujuanBalas ? [new Address($tujuanBalas, config('pengaduan.nama_bank'))] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-diterima',
            with: [
                'bank' => config('pengaduan.nama_bank'),
                'emailBantuan' => config('pengaduan.email_tujuan'),
            ],
        );
    }
}
