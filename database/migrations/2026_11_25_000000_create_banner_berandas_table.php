<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_berandas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 160);
            $table->text('teks')->nullable();
            $table->string('alt');
            $table->string('gambar_desktop');
            $table->string('gambar_mobile')->nullable();
            $table->unsignedTinyInteger('durasi_detik')->default(6);
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['aktif', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_berandas');
    }
};
