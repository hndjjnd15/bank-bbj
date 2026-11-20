<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popup_promos', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 160);
            $table->string('gambar');
            $table->string('alt');
            $table->string('tautan', 2048)->default('/pengajuan-kredit');
            $table->string('tautan_kustom', 2048)->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['aktif', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popup_promos');
    }
};
