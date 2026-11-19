<?php

// Pengaturan formulir pengajuan kredit.
// Nilai rahasia (API key WhatsApp) tetap diambil dari file .env, bukan ditulis di sini.
return [
    // Nama bank yang tampil di teks formulir dan email
    'nama_bank' => env('PENGAJUAN_KREDIT_NAMA_BANK', 'Bank BBJ'),

    // Awalan nomor tiket, hasilnya: BBJ-20261118-3F7A2C
    'kode_awalan' => env('PENGAJUAN_KREDIT_KODE_AWALAN', 'BBJ'),

    // Tujuan email pengajuan masuk (tim kredit)
    'email_tujuan' => env('PENGAJUAN_KREDIT_EMAIL_TUJUAN', 'kredit@bprbogorjabar.co.id'),

    // Alamat webmail tim kredit, dipakai sebagai tautan "Buka email" di pesan WhatsApp.
    // Sesuaikan hostnya dengan webmail kantor bila berbeda.
    'url_email' => env('PENGAJUAN_KREDIT_URL_EMAIL', 'https://mail.bprbogorjabar.co.id'),

    // Tombol "Buka di panel admin" di email internal bersifat opsional.
    // Berkas lengkap sudah ikut terlampir di email; panel admin dipakai untuk
    // mencatat tindak lanjut (proses/selesai/ditolak). Aktifkan dengan nilai true.
    'link_admin' => (bool) env('PENGAJUAN_KREDIT_LINK_ADMIN', false),

    // Notifikasi WhatsApp lewat WASender (POST JSON: api_key, sender, number, message)
    'wa' => [
        'url' => env('WASENDER_URL', 'https://wasender.web.id/send-message'),
        'api_key' => env('WASENDER_API_KEY'),
        'sender' => env('WASENDER_SENDER'),

        // Nomor penerima notifikasi pengajuan kredit (staf), pisahkan dengan koma.
        // Nomor berawalan 0 otomatis diubah ke 62 saat dikirim.
        'tujuan' => array_values(array_filter(array_map('trim', explode(',', (string) env('PENGAJUAN_KREDIT_WA_TUJUAN', '083807328349'))))),
    ],

    // Batas setiap dokumen (KB) dan format yang diizinkan
    'berkas_maks_kb' => 5120, // 5 MB
    'berkas_mimes' => 'pdf,jpg,jpeg,png',

    // Daftar produk kredit (kunci => label), dipakai di formulir, email, dan admin
    'produk' => [
        'umkm' => 'Kredit UMKM',
        'desa' => 'Kredit Pegawai Desa',
        'sertifikasi' => 'Kredit Sertifikasi',
        'pns' => 'Kredit PNS',
        'pppk' => 'Kredit PPPK',
        'honorer' => 'Kredit Honorer',
        'dewan' => 'Kredit Anggota Dewan',
        'swasta' => 'Kredit Pegawai Swasta',
        'bumn' => 'Kredit Pegawai BUMN/BUMD',
        'agunan' => 'Kredit Pegawai dengan Agunan',
        'petani' => 'Kredit Petani/Peternak',
        'kopiah' => 'Kredit KOPIAH',
    ],

    // Produk yang wajib mengisi bagian "Data Usaha"
    'produk_usaha' => ['umkm', 'petani'],

    // Produk yang wajib mengisi bagian "Data Pekerjaan"
    'produk_pekerjaan' => [
        'desa', 'sertifikasi', 'pns', 'pppk', 'honorer', 'dewan', 'swasta', 'bumn', 'agunan',
    ],

    'agama' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'],

    'jenis_kelamin' => ['Laki-laki', 'Perempuan'],

    'status_pernikahan' => [
        'menikah' => 'Menikah',
        'belum' => 'Belum Menikah',
        'cerai' => 'Cerai/Cerai Mati',
    ],

    'domisili' => [
        'ktp' => 'Sesuai KTP',
        'lain' => 'Domisili Lain',
    ],

    // Nama kolom dokumen yang bisa diunggah (kunci input => label)
    'berkas' => [
        'file_upload' => 'SKU/NIB',
        'ktp' => 'KTP',
        'file_npwp' => 'NPWP',
        'ktp_pasangan' => 'KTP Pasangan',
        'buku_nikah' => 'Buku Nikah',
        'file_npwp_pasangan' => 'NPWP Pasangan',
        'ktp_orang_tua' => 'KTP Orang Tua',
        'file_npwp_orang_tua' => 'NPWP Orang Tua',
        'akta_cerai_kematian' => 'Akta Cerai/Akta Kematian',
    ],

    // Label data pengajuan untuk email dan panel admin
    'label_data' => [
        'nama_lengkap' => 'Nama lengkap',
        'nik' => 'Nomor KTP/NIK',
        'jenis_kelamin' => 'Jenis kelamin',
        'agama' => 'Agama',
        'tempat_lahir' => 'Tempat lahir',
        'tanggal_lahir' => 'Tanggal lahir',
        'alamat_ktp' => 'Alamat KTP',
        'domisili' => 'Domisili',
        'alamat_domisili' => 'Alamat domisili',
        'npwp' => 'NPWP',
        'no_hp' => 'Nomor HP/WA',
        'nama_usaha' => 'Nama usaha',
        'lokasi_usaha' => 'Lokasi usaha',
        'instansi' => 'Nama instansi/perusahaan',
        'jabatan' => 'Jabatan',
        'status_pernikahan' => 'Status pernikahan',
        'nama_pasangan' => 'Nama pasangan',
        'hubungan_pasangan' => 'Hubungan dengan pemohon',
        'nik_pasangan' => 'NIK pasangan',
        'agama_pasangan' => 'Agama pasangan',
        'tempat_lahir_pasangan' => 'Tempat lahir pasangan',
        'tanggal_lahir_pasangan' => 'Tanggal lahir pasangan',
        'npwp_pasangan' => 'NPWP pasangan',
        'hp_pasangan' => 'Nomor HP/WA pasangan',
        'nama_orang_tua' => 'Nama orang tua',
        'hubungan_orang_tua' => 'Hubungan orang tua dengan pemohon',
        'nik_orang_tua' => 'NIK orang tua',
        'agama_orang_tua' => 'Agama orang tua',
        'tempat_lahir_orang_tua' => 'Tempat lahir orang tua',
        'tanggal_lahir_orang_tua' => 'Tanggal lahir orang tua',
        'npwp_orang_tua' => 'NPWP orang tua',
        'hp_orang_tua' => 'Nomor HP/WA orang tua',
    ],
];
