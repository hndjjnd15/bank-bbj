<?php

namespace App\Mail;

use App\Models\LaporanPelanggaran;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Tanda terima untuk pelapor. SENGAJA netral: tidak menyebut "WBS", kategori, isi laporan, atau nama,
// supaya tidak membuka kerahasiaan jika kotak email pelapor dipantau atau dipakai bersama.
class LaporanWbsDiterima extends Mailable
{
    public function __construct(public LaporanPelanggaran $laporan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tanda terima dari ' . config('pengaduan.nama_bank') . ' (' . $this->laporan->kode . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wbs-diterima',
            with: ['bank' => config('pengaduan.nama_bank')],
        );
    }
}
