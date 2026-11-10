<?php

// Pengaturan simulasi dan catatan halaman produk deposito.
return [
    // PPh final atas bunga deposito (PP 131/2000 dan PMK 212/PMK.03/2018).
    // Dikenakan 20% dari bunga bruto jika nominal deposito di atas batas bebas pajak.
    'pajak_persen' => 20,
    'batas_bebas_pajak' => 7500000,

    // Catatan penjaminan simpanan yang tampil di bawah tabel suku bunga
    'lps_catatan' => 'Simpanan nasabah dijamin LPS hingga Rp 2 miliar per nasabah per bank sesuai ketentuan yang berlaku.',

    // Catatan kaki simulasi
    'catatan_simulasi' => 'Hasil simulasi bersifat perkiraan dengan asumsi bunga dibayarkan saat jatuh tempo. Suku bunga dan ketentuan yang berlaku mengikuti kebijakan bank pada saat penempatan dan dapat berubah sewaktu-waktu. Perhitungan pajak mengacu pada ketentuan PPh final atas bunga deposito.',
];
