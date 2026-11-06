<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu baris saja: isi teks dan foto halaman profil
        Schema::create('profil_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->longText('sejarah')->nullable();
            $table->string('foto_gedung')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();            // satu baris = satu poin
            $table->string('foto_struktur')->nullable(); // gambar struktur organisasi
            $table->timestamps();
        });

        // Struktur permodalan. Persentase dihitung otomatis dari dua nilai ini.
        Schema::create('pemegang_sahams', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('modal_dasar');
            $table->unsignedBigInteger('modal_disetor');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        // Dewan Komisaris dan Direksi
        Schema::create('pengurus_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('kelompok');                  // komisaris atau direksi
            $table->string('foto')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus_perusahaans');
        Schema::dropIfExists('pemegang_sahams');
        Schema::dropIfExists('profil_perusahaans');
    }
};
