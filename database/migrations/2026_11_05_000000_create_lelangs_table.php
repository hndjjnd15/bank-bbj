<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lelangs', function (Blueprint $table) {
            $table->id();

            // 1. Identitas jaminan & status lelang
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('tipe_aset');
            $table->string('sertifikat');
            $table->string('status')->default('lelang');   // lelang, ayda, terjual
            $table->dateTime('jadwal_lelang')->nullable();

            // 2. Spesifikasi fisik
            $table->decimal('luas_tanah', 10, 2)->nullable();
            $table->decimal('luas_bangunan', 10, 2)->nullable();

            // 3. Lokasi
            $table->text('alamat');
            $table->string('kota');
            $table->string('koordinat')->nullable();       // "latitude, longitude"
            $table->string('link_maps')->nullable();

            // 4. Nilai agunan
            $table->unsignedBigInteger('harga_limit');
            $table->unsignedBigInteger('harga_pasar')->nullable();

            // 5. Kontak marketing & sosmed
            $table->string('pic_nama');
            $table->string('pic_whatsapp');
            $table->string('link_instagram')->nullable();

            // 6. Deskripsi
            $table->longText('deskripsi');

            // 7. Foto
            $table->string('foto_utama')->nullable();
            $table->json('galeri')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lelangs');
    }
};
