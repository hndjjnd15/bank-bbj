<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_csrs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori');
            $table->date('tanggal');                        // tanggal pelaksanaan
            $table->string('lokasi')->nullable();
            $table->string('penerima_manfaat')->nullable(); // contoh: 150 siswa SD
            $table->string('mitra')->nullable();            // contoh: bersama Dinas Pendidikan
            $table->string('ringkasan')->nullable();
            $table->longText('isi');
            $table->string('isi_rata')->default('rata');    // perataan teks deskripsi
            $table->string('foto_utama')->nullable();
            $table->json('galeri')->nullable();
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_csrs');
    }
};
