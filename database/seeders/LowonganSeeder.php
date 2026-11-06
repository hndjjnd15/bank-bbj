<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        // Semua data di bawah hanya CONTOH untuk uji tampilan (email dan nomor WhatsApp juga placeholder).
        // Hapus lewat /admin sebelum website tayang.
        $dasar = [
            'email_lamaran' => 'karir@example.com',
            'wa_lamaran' => '081234567890',
            'catatan_lamaran' => 'Lampirkan CV, pas foto terbaru, fotokopi KTP, dan ijazah terakhir. Cantumkan nama posisi pada subjek email.',
            'deskripsi' => "Data contoh.\n\nBertanggung jawab melayani nasabah dengan ramah dan profesional serta menjaga kualitas layanan di unit kerja.",
            'benefit' => "Gaji sesuai ketentuan perusahaan\nTunjangan dan asuransi kesehatan\nPelatihan dan pengembangan karier",
        ];

        $data = [
            ['judul' => 'Teller', 'divisi' => 'Operasional', 'penempatan' => 'Cabang Parungpanjang', 'tipe_kerja' => 'Karyawan Tetap', 'pendidikan_minimal' => 'D3', 'pengalaman' => 'Fresh graduate dipersilakan', 'batas_lamaran' => now()->addDays(20)->toDateString(), 'persyaratan' => "Pendidikan minimal D3 semua jurusan\nTeliti dan jujur\nBersedia ditempatkan di cabang\nMenguasai Microsoft Office"],
            ['judul' => 'Customer Service', 'divisi' => 'Layanan Nasabah', 'penempatan' => 'Cabang Sawangan', 'tipe_kerja' => 'Kontrak', 'pendidikan_minimal' => 'S1', 'pengalaman' => 'Minimal 1 tahun', 'batas_lamaran' => now()->addDays(12)->toDateString(), 'persyaratan' => "Pendidikan minimal S1\nKomunikasi baik dan ramah\nPengalaman di bidang layanan lebih disukai"],
            ['judul' => 'Account Officer Kredit', 'divisi' => 'Kredit', 'penempatan' => 'Cabang Leuwiliang', 'tipe_kerja' => 'Karyawan Tetap', 'pendidikan_minimal' => 'S1', 'pengalaman' => 'Minimal 2 tahun', 'batas_lamaran' => now()->addDays(30)->toDateString(), 'persyaratan' => "Pendidikan minimal S1\nPengalaman di bidang kredit mikro\nMemiliki SIM C dan kendaraan pribadi\nBersedia melakukan survei lapangan"],
            ['judul' => 'Staf Administrasi Kredit', 'divisi' => 'Kredit', 'penempatan' => 'Cabang Citeureup', 'tipe_kerja' => 'Magang', 'pendidikan_minimal' => 'SMA / SMK', 'pengalaman' => 'Tanpa pengalaman', 'batas_lamaran' => null, 'persyaratan' => "Lulusan SMA/SMK semua jurusan\nTeliti dan rapi dalam administrasi\nBisa mengoperasikan komputer"],
            ['judul' => 'Staf IT Support', 'divisi' => 'Teknologi Informasi', 'penempatan' => 'Kantor Pusat', 'tipe_kerja' => 'Karyawan Tetap', 'pendidikan_minimal' => 'D3', 'pengalaman' => 'Minimal 1 tahun', 'batas_lamaran' => now()->subDays(5)->toDateString(), 'persyaratan' => "Pendidikan minimal D3 Teknik Informatika atau sejenis\nMemahami jaringan dan perangkat komputer\nSiap menangani gangguan sistem"],
            ['judul' => 'Satpam', 'divisi' => 'Umum', 'penempatan' => 'Cabang Parungpanjang', 'tipe_kerja' => 'Kontrak', 'pendidikan_minimal' => 'SMA / SMK', 'pengalaman' => 'Memiliki sertifikat Gada Pratama', 'batas_lamaran' => now()->addDays(8)->toDateString(), 'dibuka' => false, 'persyaratan' => "Memiliki sertifikat Gada Pratama\nSehat jasmani dan rohani\nSiap bekerja dengan sistem shift"],
        ];

        foreach ($data as $d) {
            $row = array_merge($dasar, ['dibuka' => true], $d);
            $row['slug'] = Str::slug($row['judul'] . ' ' . $row['penempatan']);
            Lowongan::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
