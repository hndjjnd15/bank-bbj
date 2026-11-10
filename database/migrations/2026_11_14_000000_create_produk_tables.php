<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Konten halaman produk (deposito sekarang; tabungan dan kredit bisa memakai tabel yang sama)
        Schema::create('produk_banks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');                        // deposito, tabungan, kredit
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->string('deskripsi_rata')->default('rata');
            $table->unsignedBigInteger('setoran_minimal')->nullable();
            $table->text('keunggulan')->nullable();         // satu baris = satu poin
            $table->text('persyaratan')->nullable();        // satu baris = satu poin
            $table->text('ketentuan')->nullable();          // satu baris = satu poin
            $table->string('foto_utama')->nullable();
            $table->json('flyer')->nullable();              // gambar flyer (bisa lebih dari satu)
            $table->string('brosur_pdf')->nullable();       // berkas brosur untuk diunduh
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();
        });

        // Tabel suku bunga deposito: satu baris = satu kombinasi tenor dan rentang nominal
        Schema::create('suku_bunga_depositos', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tenor_bulan');
            $table->unsignedBigInteger('nominal_min');
            $table->unsignedBigInteger('nominal_maks')->nullable();   // batas atas (tidak termasuk); kosong = tanpa batas
            $table->decimal('bunga', 5, 2);                          // persen per tahun (p.a.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suku_bunga_depositos');
        Schema::dropIfExists('produk_banks');
    }
};
