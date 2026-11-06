<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();          // kode tiket, contoh: PGD-261103-K7QX2
            $table->string('nama');
            $table->string('telepon');
            $table->string('email')->nullable();
            $table->string('jenis');                   // kategori pengaduan
            $table->text('isi');
            $table->string('status')->default('baru'); // baru, diproses, selesai
            $table->text('catatan')->nullable();       // catatan internal admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
