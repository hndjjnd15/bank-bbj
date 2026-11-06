<?php

// Pengaturan fitur pengaduan. Nilai rahasia (API key, email) diisi lewat file .env, bukan di sini.
return [
    // Nama bank yang tampil di teks formulir
    'nama_bank' => env('PENGADUAN_NAMA_BANK', 'Bank BBJ'),

    // Awalan kode tiket. Hasilnya: BBJ-20261104-K7QX2 (awalan-tahunbulantanggal-kode acak)
    'kode_awalan' => env('PENGADUAN_KODE_AWALAN', 'BBJ'),

    // Tujuan email setiap pengaduan masuk
    'email_tujuan' => env('PENGADUAN_EMAIL_TUJUAN', 'sobatcare@bprbogorjabar.co.id'),

    // Notifikasi WhatsApp lewat WASender (POST JSON: api_key, sender, number, message)
    'wa' => [
        'url' => env('WASENDER_URL', 'https://wasender.web.id/send-message'),
        'api_key' => env('WASENDER_API_KEY'),
        'sender' => env('WASENDER_SENDER'),      // nomor perangkat yang terhubung di WASender
        // Nomor penerima notifikasi (staf), pisahkan dengan koma. Contoh: 628123456789,628987654321
        'tujuan' => array_values(array_filter(array_map('trim', explode(',', (string) env('WASENDER_TUJUAN', ''))))),
    ],

    // Pilihan di formulir
    'jenis' => ['Pengaduan', 'Saran & Masukan'],
    'cabang' => ['Cabang Parungpanjang', 'Cabang Leuwiliang', 'Cabang Sawangan', 'Cabang Citeureup'],
    'kategori' => ['Pelayanan', 'Transaksi', 'Produk Bank', 'Informasi', 'Fasilitas', 'Saran dan Pengembangan', 'Lainnya'],

    // Lampiran bukti
    'lampiran_mimes' => 'jpg,jpeg,png,pdf',
    'lampiran_maks_kb' => 5120, // 5 MB
];
