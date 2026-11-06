<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id();

            // Informasi lowongan
            $table->string('judul');                       // nama posisi
            $table->string('slug')->unique();
            $table->string('divisi')->nullable();
            $table->string('penempatan');                  // contoh: Cabang Parungpanjang
            $table->string('tipe_kerja');
            $table->string('pendidikan_minimal')->nullable();
            $table->string('pengalaman')->nullable();      // contoh: Fresh graduate / min. 1 tahun
            $table->date('batas_lamaran')->nullable();
            $table->boolean('dibuka')->default(true);

            // Isi
            $table->longText('deskripsi');                 // tanggung jawab pekerjaan
            $table->longText('persyaratan');               // satu baris = satu poin
            $table->longText('benefit')->nullable();       // satu baris = satu poin

            // Cara melamar
            $table->string('email_lamaran');
            $table->string('wa_lamaran')->nullable();
            $table->text('catatan_lamaran')->nullable();   // contoh: dokumen yang harus dilampirkan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
