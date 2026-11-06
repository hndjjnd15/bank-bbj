<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_tahunans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->unique();   // satu baris per tahun

            // Alamat file di storage publik. Kosong = "Tidak Ada Data" di website.
            $table->string('file_laporan_tahunan')->nullable();
            $table->string('file_tata_kelola')->nullable();
            $table->string('file_keuangan_berkelanjutan')->nullable();
            $table->string('file_ppid')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_tahunans');
    }
};
