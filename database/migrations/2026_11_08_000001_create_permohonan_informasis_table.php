<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_informasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();               // nomor registrasi, contoh: PPID-20261108-K7QX2

            $table->string('jenis_pemohon');                // perorangan, kelompok, badan hukum
            $table->string('nama');
            $table->text('nomor_identitas');                // dienkripsi (KTP / NPWP)
            $table->text('alamat');                         // dienkripsi
            $table->string('pekerjaan')->nullable();
            $table->string('telepon');
            $table->string('email');

            $table->text('rincian');                        // informasi yang diminta
            $table->text('tujuan');                         // tujuan penggunaan informasi
            $table->string('cara_memperoleh');

            $table->string('status')->default('baru');      // baru, diproses, dipenuhi, ditolak
            $table->text('catatan')->nullable();            // catatan internal petugas

            $table->timestamp('email_terkirim_at')->nullable();
            $table->timestamp('wa_terkirim_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_informasis');
    }
};
