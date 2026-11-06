<?php

namespace App\Console\Commands;

use App\Services\PengaduanNotifier;
use Illuminate\Console\Command;

class TesWhatsApp extends Command
{
    protected $signature = 'pengaduan:tes-wa';

    protected $description = 'Kirim pesan percobaan WhatsApp lewat WASender untuk mengecek pengaturan';

    public function handle(PengaduanNotifier $notifier): int
    {
        $this->info('Mengirim pesan percobaan ke nomor di WASENDER_TUJUAN...');

        $hasil = $notifier->kirimPesan('Tes notifikasi pengaduan. Jika pesan ini masuk, WASender sudah terhubung dengan benar.');

        if ($hasil['ok']) {
            $this->info('BERHASIL: ' . $hasil['ringkas']);

            return self::SUCCESS;
        }

        $this->error('GAGAL: ' . $hasil['ringkas']);

        return self::FAILURE;
    }
}
