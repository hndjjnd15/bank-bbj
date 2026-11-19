<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk_banks', function (Blueprint $table) {
            if (! Schema::hasColumn('produk_banks', 'simulasi_aktif')) {
                $table->boolean('simulasi_aktif')->default(false);
            }

            if (! Schema::hasColumn('produk_banks', 'suku_bunga_kredit')) {
                $table->decimal('suku_bunga_kredit', 6, 2)->nullable();
            }

            if (! Schema::hasColumn('produk_banks', 'kontak_whatsapp')) {
                $table->string('kontak_whatsapp', 32)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('produk_banks', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('produk_banks', 'simulasi_aktif') ? 'simulasi_aktif' : null,
                Schema::hasColumn('produk_banks', 'suku_bunga_kredit') ? 'suku_bunga_kredit' : null,
                Schema::hasColumn('produk_banks', 'kontak_whatsapp') ? 'kontak_whatsapp' : null,
            ]);

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
