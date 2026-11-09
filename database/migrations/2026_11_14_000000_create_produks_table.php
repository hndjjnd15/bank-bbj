<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel umum untuk halaman produk (deposito sekarang; tabungan dan kredit bisa menyusul)
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');                          // deposito, tabungan, kredit
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('fitur')->nullable();                // keunggulan, satu baris = satu poin
            $table->text('syarat')->nullable();               // persyaratan, satu baris = satu poin
            $table->text('ketentuan')->nullable();            // ketentuan dan biaya, satu baris = satu poin

            // Khusus deposito: tabel suku bunga + kalkulator
            $table->unsignedBigInteger('nominal_minimal')->nullable();
            $table->date('bunga_berlaku_sejak')->nullable();
            $table->json('suku_bunga')->nullable();           // [{tenor_bulan, nominal_min, nominal_maks, bunga}]
            $table->decimal('pajak_persen', 5, 2)->nullable();
            $table->unsignedBigInteger('batas_bebas_pajak')->nullable();
            $table->text('catatan')->nullable();              // catatan di bawah tabel bunga

            // Brosur / flyer
            $table->json('brosur_gambar')->nullable();        // gambar flyer untuk pratinjau
            $table->string('brosur_pdf')->nullable();         // berkas PDF untuk diunduh

            $table->json('faq')->nullable();                  // [{tanya, jawab}]
            $table->string('wa_marketing')->nullable();
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
