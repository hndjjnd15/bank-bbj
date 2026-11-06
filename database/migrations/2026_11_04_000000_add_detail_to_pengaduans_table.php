<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada sama sekali, buat lengkap
        if (! Schema::hasTable('pengaduans')) {
            Schema::create('pengaduans', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama');
                $table->string('telepon');
                $table->string('email')->nullable();
                $table->string('jenis');                   // Pengaduan atau Saran & Masukan
                $table->text('isi');
                $table->string('status')->default('baru');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // Tambahkan kolom baru (dilewati jika sudah ada)
        Schema::table('pengaduans', function (Blueprint $table) {
            if (! Schema::hasColumn('pengaduans', 'nik')) {
                $table->text('nik')->nullable();            // text: nilainya dienkripsi
            }
            if (! Schema::hasColumn('pengaduans', 'no_rekening')) {
                $table->text('no_rekening')->nullable();    // text: nilainya dienkripsi
            }
            if (! Schema::hasColumn('pengaduans', 'cabang')) {
                $table->string('cabang')->nullable();
            }
            if (! Schema::hasColumn('pengaduans', 'tanggal_kejadian')) {
                $table->date('tanggal_kejadian')->nullable();
            }
            if (! Schema::hasColumn('pengaduans', 'kategori')) {
                $table->string('kategori')->nullable();
            }
            if (! Schema::hasColumn('pengaduans', 'lampiran')) {
                $table->string('lampiran')->nullable();     // alamat file di storage privat
            }
            if (! Schema::hasColumn('pengaduans', 'email_terkirim_at')) {
                $table->timestamp('email_terkirim_at')->nullable();
            }
            if (! Schema::hasColumn('pengaduans', 'wa_terkirim_at')) {
                $table->timestamp('wa_terkirim_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'no_rekening', 'cabang', 'tanggal_kejadian',
                'kategori', 'lampiran', 'email_terkirim_at', 'wa_terkirim_at',
            ]);
        });
    }
};
