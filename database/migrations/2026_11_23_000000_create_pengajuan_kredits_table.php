<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kredits', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();               // nomor tiket, contoh: BBJ-20261118-3F7A2C

            // Kolom yang sering dipakai untuk pencarian dan daftar di admin
            $table->string('nama_lengkap');
            $table->text('nik');                            // dienkripsi (16 digit KTP)
            $table->string('email');
            $table->string('no_hp', 30);
            $table->string('jenis_kredit', 30);             // kunci produk, contoh: umkm
            $table->string('nama_produk');                  // label produk, contoh: Kredit UMKM
            $table->unsignedBigInteger('nominal_kredit');
            $table->string('status_pernikahan', 20)->nullable();
            $table->string('domisili', 10)->nullable();
            $table->text('alamat_domisili')->nullable();    // dienkripsi

            // Seluruh isian formulir (termasuk bagian usaha, pekerjaan, dan keluarga).
            // Disimpan sebagai JSON terenkripsi supaya data pribadi tidak terbaca langsung di database.
            $table->longText('data');

            // Daftar dokumen unggahan: kunci => path, nama asli, ukuran, dan mime.
            $table->longText('berkas')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->string('status')->default('baru');      // baru, diproses, selesai
            $table->text('catatan')->nullable();            // catatan internal petugas

            $table->timestamp('email_terkirim_at')->nullable();
            $table->timestamp('wa_terkirim_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kredits');
    }
};
