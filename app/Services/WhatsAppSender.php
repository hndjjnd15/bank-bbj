<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class WhatsAppSender
{
    // Kirim satu pesan lewat WASender. $wa berisi: url, api_key, sender, tujuan (daftar nomor).
    // Format WASender: POST JSON { api_key, sender, number, message }; beberapa penerima dipisah "|".
    public function kirim(array $wa, string $pesan): array
    {
        if (blank($wa['api_key'] ?? null) || blank($wa['sender'] ?? null) || empty($wa['tujuan'] ?? [])) {
            return ['ok' => false, 'ringkas' => 'Pengaturan WhatsApp belum lengkap (API key, nomor pengirim, atau nomor tujuan belum diisi di .env).'];
        }

        try {
            $respons = Http::asJson()->timeout(15)->post($wa['url'], [
                'api_key' => $wa['api_key'],
                'sender' => $wa['sender'],
                'number' => implode('|', $wa['tujuan']),
                'message' => $pesan,
            ]);
        } catch (Throwable $e) {
            return ['ok' => false, 'ringkas' => 'Tidak bisa terhubung ke WASender: ' . $e->getMessage()];
        }

        // Sebagian API membalas HTTP 200 tetapi berisi status gagal di dalam JSON
        $json = $respons->json();
        $statusGagal = is_array($json)
            && array_key_exists('status', $json)
            && in_array($json['status'], [false, 'false', 0, '0', 'error', 'failed'], true);

        return [
            'ok' => $respons->successful() && ! $statusGagal,
            'ringkas' => 'HTTP ' . $respons->status() . ' | Balasan: ' . Str::limit($respons->body(), 300),
        ];
    }
}
