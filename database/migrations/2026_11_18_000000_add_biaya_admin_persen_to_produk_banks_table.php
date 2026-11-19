<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom ini sengaja dipisah dari migrasi field kredit lainnya: database yang
     * sudah menjalankan migrasi sebelumnya tetap perlu migrasi baru agar kolom
     * biaya_admin_persen ikut dibuat.
     */
    public function up(): void
    {
        if (Schema::hasColumn('produk_banks', 'biaya_admin_persen')) {
            return;
        }

        Schema::table('produk_banks', function (Blueprint $table) {
            $table->decimal('biaya_admin_persen', 5, 2)->default(0);
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('produk_banks', 'biaya_admin_persen')) {
            return;
        }

        Schema::table('produk_banks', function (Blueprint $table) {
            $table->dropColumn('biaya_admin_persen');
        });
    }
};
