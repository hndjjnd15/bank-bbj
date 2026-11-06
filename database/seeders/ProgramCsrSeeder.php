<?php

namespace Database\Seeders;

use App\Models\ProgramCsr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramCsrSeeder extends Seeder
{
    public function run(): void
    {
        // Semua data di bawah hanya CONTOH untuk uji tampilan. Hapus atau ganti lewat /admin sebelum website tayang.
        $isi = "Teks contoh. Tuliskan uraian kegiatan di panel admin: latar belakang program, apa yang dilakukan, dan dampaknya bagi penerima manfaat.\n\nPisahkan paragraf dengan satu baris kosong. Foto kegiatan dapat ditambahkan sebagai foto utama dan galeri.";

        $data = [
            ['judul' => 'Bantuan Perlengkapan Sekolah (Contoh)', 'kategori' => 'Pendidikan', 'tanggal' => '2026-10-20', 'lokasi' => 'Kabupaten Bogor', 'penerima_manfaat' => '150 siswa', 'mitra' => 'Dinas Pendidikan (contoh)', 'ringkasan' => 'Penyaluran perlengkapan sekolah untuk siswa kurang mampu.', 'foto_utama' => '/images/contoh/foto-1.svg', 'galeri' => ['/images/contoh/foto-2.svg', '/images/contoh/foto-3.svg']],
            ['judul' => 'Pemeriksaan Kesehatan Gratis (Contoh)', 'kategori' => 'Kesehatan', 'tanggal' => '2026-09-14', 'lokasi' => 'Parungpanjang', 'penerima_manfaat' => '200 warga', 'ringkasan' => 'Pemeriksaan kesehatan dasar untuk masyarakat sekitar.', 'foto_utama' => '/images/contoh/foto-2.svg', 'galeri' => ['/images/contoh/foto-3.svg']],
            ['judul' => 'Aksi Tanam Pohon (Contoh)', 'kategori' => 'Lingkungan', 'tanggal' => '2026-08-30', 'lokasi' => 'Leuwiliang', 'penerima_manfaat' => 'Masyarakat sekitar', 'ringkasan' => 'Penanaman pohon untuk menjaga kelestarian lingkungan.', 'foto_utama' => '/images/contoh/foto-3.svg'],
            ['judul' => 'Pelatihan Usaha Mikro (Contoh)', 'kategori' => 'Ekonomi dan UMKM', 'tanggal' => '2026-07-18', 'lokasi' => 'Sawangan', 'penerima_manfaat' => '60 pelaku usaha', 'ringkasan' => 'Pelatihan pengelolaan keuangan untuk pelaku UMKM.', 'foto_utama' => '/images/contoh/foto-1.svg', 'galeri' => ['/images/contoh/foto-2.svg']],
            ['judul' => 'Santunan Anak Yatim (Contoh)', 'kategori' => 'Sosial dan Kemanusiaan', 'tanggal' => '2026-06-05', 'lokasi' => 'Citeureup', 'penerima_manfaat' => '80 anak', 'ringkasan' => 'Santunan dan bantuan kebutuhan pokok.', 'foto_utama' => '/images/contoh/foto-2.svg'],
            ['judul' => 'Bantuan Sarana Ibadah (Contoh)', 'kategori' => 'Keagamaan', 'tanggal' => '2025-12-12', 'lokasi' => 'Kabupaten Bogor', 'ringkasan' => 'Bantuan perlengkapan untuk sarana ibadah.', 'foto_utama' => null],
            ['judul' => 'Beasiswa Siswa Berprestasi (Contoh)', 'kategori' => 'Pendidikan', 'tanggal' => '2025-08-17', 'lokasi' => 'Kabupaten Bogor', 'penerima_manfaat' => '25 siswa', 'ringkasan' => 'Beasiswa untuk siswa berprestasi dari keluarga kurang mampu.', 'foto_utama' => '/images/contoh/foto-3.svg'],
        ];

        foreach ($data as $d) {
            $row = $d + ['isi' => $isi, 'isi_rata' => 'rata', 'diterbitkan' => true];
            $row['slug'] = Str::slug($row['judul']);
            ProgramCsr::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
