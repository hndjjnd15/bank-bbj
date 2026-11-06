<?php

// Pengaturan layanan PPID (Pejabat Pengelola Informasi dan Dokumentasi).
// Nilai rahasia dan kontak diisi lewat .env, bukan di sini.
return [
    // Awalan nomor registrasi permohonan. Hasilnya: PPID-20261108-K7QX2
    'kode_awalan' => env('PPID_KODE_AWALAN', 'PPID'),

    // Tujuan email permohonan informasi. Wajib diisi di .env.
    'email_tujuan' => env('PPID_EMAIL_TUJUAN'),

    // Kirim email tanda terima ke pemohon (isinya hanya nomor registrasi dan ketentuan waktu)
    'balas_pemohon' => (bool) env('PPID_BALAS_PEMOHON', true),

    // Notifikasi WhatsApp lewat WASender. Nomor penerima khusus PPID.
    // API key dan nomor pengirim mengikuti pengaturan pengaduan, kecuali diisi sendiri di sini.
    'wa' => [
        'url' => env('PPID_WASENDER_URL', env('WASENDER_URL', 'https://wasender.web.id/send-message')),
        'api_key' => env('PPID_WASENDER_API_KEY', env('WASENDER_API_KEY')),
        'sender' => env('PPID_WASENDER_SENDER', env('WASENDER_SENDER')),
        'tujuan' => array_values(array_filter(array_map('trim', explode(',', (string) env('PPID_WASENDER_TUJUAN', ''))))),
    ],

    // Kontak yang tampil di halaman PPID (kosongkan yang tidak ada)
    'kontak' => [
        'email' => env('PPID_KONTAK_EMAIL'),
        'telepon' => env('PPID_KONTAK_TELEPON'),
        'alamat' => env('PPID_KONTAK_ALAMAT'),
        'jam' => env('PPID_KONTAK_JAM', 'Senin - Jumat, 08.00 - 16.00 WIB'),
    ],

    // Ketentuan waktu (UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik)
    'tenggat_hari_kerja' => 10,       // tanggapan tertulis
    'perpanjangan_hari_kerja' => 7,   // perpanjangan tanggapan

    // Pilihan di formulir
    'jenis_pemohon' => ['Perorangan', 'Kelompok Orang', 'Badan Hukum'],
    'cara_memperoleh' => [
        'Melihat / membaca / mencatat informasi',
        'Salinan softcopy (dikirim lewat email)',
        'Salinan hardcopy (diambil langsung di kantor)',
        'Salinan hardcopy (dikirim lewat pos atau kurir)',
    ],

    // Kategori dokumen informasi publik
    'kategori' => [
        'berkala' => 'Informasi Berkala',
        'serta_merta' => 'Informasi Serta Merta',
        'setiap_saat' => 'Informasi Setiap Saat',
    ],
];
