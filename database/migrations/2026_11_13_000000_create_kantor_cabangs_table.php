<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kantor_cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('tipe');                          // Kantor Pusat, Kantor Cabang, Kantor Kas
            $table->text('alamat');
            $table->string('kota');                          // kota / kabupaten / kecamatan
            $table->string('telepon')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('jam_operasional')->nullable();     // satu baris = satu jadwal
            $table->string('koordinat')->nullable();         // "latitude, longitude"
            $table->string('link_maps')->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('galeri')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kantor_cabangs');
    }
};
