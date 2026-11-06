<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_triwulans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('periode');   // bulan akhir triwulan: 3, 6, 9, atau 12
            $table->json('berkas')->nullable();       // daftar berkas: [{judul, file}, ...]
            $table->timestamps();

            $table->unique(['tahun', 'periode']);     // satu data per tahun dan periode
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_triwulans');
    }
};
