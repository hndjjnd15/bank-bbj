<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('suku_bunga_depositos') || ! Schema::hasColumn('suku_bunga_depositos', 'nominal_min')) {
            return;
        }

        DB::table('suku_bunga_depositos')->delete();

        Schema::table('suku_bunga_depositos', function (Blueprint $table) {
            $table->foreignId('produk_bank_id')->after('id')->constrained('produk_banks')->cascadeOnDelete();
            $table->dropColumn(['nominal_min', 'nominal_maks']);
            $table->unique(['produk_bank_id', 'tenor_bulan'], 'suku_bunga_produk_tenor_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('suku_bunga_depositos') || ! Schema::hasColumn('suku_bunga_depositos', 'produk_bank_id')) {
            return;
        }

        Schema::table('suku_bunga_depositos', function (Blueprint $table) {
            $table->dropUnique('suku_bunga_produk_tenor_unique');
            $table->dropForeign(['produk_bank_id']);
            $table->dropColumn('produk_bank_id');
            $table->unsignedBigInteger('nominal_min')->nullable();
            $table->unsignedBigInteger('nominal_maks')->nullable();
        });
    }
};
