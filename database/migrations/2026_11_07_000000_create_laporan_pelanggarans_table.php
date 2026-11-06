<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();               // contoh: WBS-20261107-K7QX2

            // Identitas pelapor: dienkripsi, karena itu bertipe text
            $table->text('nama_pelapor')->nullable();
            $table->text('email');
            $table->text('telepon')->nullable();

            $table->string('hubungan');                      // hubungan dengan bank
            $table->string('kategori');
            $table->date('tanggal_kejadian')->nullable();
            $table->string('lokasi')->nullable();            // lokasi / unit kerja
            $table->longText('uraian');                      // dienkripsi
            $table->string('lampiran')->nullable();          // alamat file di storage privat

            $table->string('status')->default('baru');       // baru, diverifikasi, ditindaklanjuti, selesai
            $table->text('catatan')->nullable();             // catatan internal tim

            $table->timestamp('email_terkirim_at')->nullable();
            $table->timestamp('wa_terkirim_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pelanggarans');
    }
};
