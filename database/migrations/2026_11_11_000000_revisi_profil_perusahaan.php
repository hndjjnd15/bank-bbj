<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pilihan perataan teks sejarah, visi, dan misi (bawaan: rata kanan-kiri / justify)
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            foreach (['sejarah_rata', 'visi_rata', 'misi_rata'] as $kolom) {
                if (! Schema::hasColumn('profil_perusahaans', $kolom)) {
                    $table->string($kolom)->default('rata');
                }
            }
        });

        // Latar belakang dan pendidikan pengurus
        Schema::table('pengurus_perusahaans', function (Blueprint $table) {
            if (! Schema::hasColumn('pengurus_perusahaans', 'latar_belakang')) {
                $table->text('latar_belakang')->nullable();
            }
            if (! Schema::hasColumn('pengurus_perusahaans', 'pendidikan')) {
                $table->text('pendidikan')->nullable();   // satu baris = satu jenjang
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['sejarah_rata', 'visi_rata', 'misi_rata']);
        });
        Schema::table('pengurus_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['latar_belakang', 'pendidikan']);
        });
    }
};
