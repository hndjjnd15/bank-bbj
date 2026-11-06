<?php

namespace App\Console\Commands;

use App\Services\WhatsAppSender;
use Illuminate\Console\Command;

class TesPpidWhatsApp extends Command
{
    protected $signature = 'ppid:tes-wa';

    protected $description = 'Kirim pesan percobaan WhatsApp PPID ke nomor di PPID_WASENDER_TUJUAN';

    public function handle(WhatsAppSender $pengirim): int
    {
        $this->info('Mengirim pesan percobaan ke nomor di PPID_WASENDER_TUJUAN...');

        $hasil = $pengirim->kirim(config('ppid.wa'), 'Tes notifikasi PPID. Jika pesan ini masuk, pengaturan WhatsApp PPID sudah benar.');

        if ($hasil['ok']) {
            $this->info('BERHASIL: ' . $hasil['ringkas']);

            return self::SUCCESS;
        }

        $this->error('GAGAL: ' . $hasil['ringkas']);

        return self::FAILURE;
    }
}
