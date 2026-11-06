<?php

namespace App\Console\Commands;

use App\Services\WhatsAppSender;
use Illuminate\Console\Command;

class TesWbsWhatsApp extends Command
{
    protected $signature = 'wbs:tes-wa';

    protected $description = 'Kirim pesan percobaan WhatsApp WBS ke nomor di WBS_WASENDER_TUJUAN';

    public function handle(WhatsAppSender $pengirim): int
    {
        $this->info('Mengirim pesan percobaan ke nomor di WBS_WASENDER_TUJUAN...');

        $hasil = $pengirim->kirim(config('wbs.wa'), 'Tes notifikasi WBS. Jika pesan ini masuk, pengaturan WhatsApp WBS sudah benar.');

        if ($hasil['ok']) {
            $this->info('BERHASIL: ' . $hasil['ringkas']);

            return self::SUCCESS;
        }

        $this->error('GAGAL: ' . $hasil['ringkas']);

        return self::FAILURE;
    }
}
