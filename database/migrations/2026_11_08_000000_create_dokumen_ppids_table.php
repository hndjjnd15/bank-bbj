<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_ppids', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');                   // berkala, serta_merta, setiap_saat
            $table->unsignedSmallInteger('tahun');
            $table->date('tanggal_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file')->nullable();           // alamat file di storage publik
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_ppids');
    }
};
