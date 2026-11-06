<?php

// Pengaturan Whistleblowing System (WBS). Nilai rahasia diisi lewat .env, bukan di sini.
return [
    // Awalan kode laporan. Hasilnya: WBS-20261107-K7QX2
    'kode_awalan' => env('WBS_KODE_AWALAN', 'WBS'),

    // Tujuan email laporan WBS (berbeda dari email pengaduan nasabah). Wajib diisi di .env.
    'email_tujuan' => env('WBS_EMAIL_TUJUAN'),

    // Jika false (disarankan), nama, email, dan telepon pelapor TIDAK dicantumkan di email ke tim.
    // Identitas hanya terlihat di panel admin, sehingga akses ke identitas pelapor lebih terkendali.
    'sertakan_identitas' => (bool) env('WBS_SERTAKAN_IDENTITAS', false),

    // Kirim email tanda terima ke pelapor (isinya hanya kode laporan, tanpa detail apa pun).
    // Set false di .env (WBS_BALAS_PELAPOR=false) untuk mematikannya.
    'balas_pelapor' => (bool) env('WBS_BALAS_PELAPOR', true),

    // Notifikasi WhatsApp lewat WASender. Nomor penerima berbeda dari pengaduan nasabah.
    // API key dan nomor pengirim mengikuti pengaturan pengaduan, kecuali diisi sendiri di sini.
    'wa' => [
        'url' => env('WBS_WASENDER_URL', env('WASENDER_URL', 'https://wasender.web.id/send-message')),
        'api_key' => env('WBS_WASENDER_API_KEY', env('WASENDER_API_KEY')),
        'sender' => env('WBS_WASENDER_SENDER', env('WASENDER_SENDER')),
        // Nomor penerima notifikasi WBS, pisahkan dengan koma. Contoh: 628123456789,628987654321
        'tujuan' => array_values(array_filter(array_map('trim', explode(',', (string) env('WBS_WASENDER_TUJUAN', ''))))),
    ],

    // Pilihan di formulir (ubah sesuai kebutuhan bank)
    'hubungan' => ['Karyawan', 'Nasabah', 'Mitra / Vendor', 'Masyarakat Umum', 'Lainnya'],
    'kategori' => [
        'Kecurangan (Fraud)',
        'Korupsi, Suap, atau Gratifikasi',
        'Benturan Kepentingan',
        'Pelanggaran Kode Etik',
        'Pelanggaran Peraturan atau Ketentuan',
        'Penyalahgunaan Wewenang atau Aset',
        'Lainnya',
    ],

    // Lampiran bukti
    'lampiran_mimes' => 'jpg,jpeg,png,pdf',
    'lampiran_maks_kb' => 5120, // 5 MB
];
