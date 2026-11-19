<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'produk_bank_id')) {
                $table->foreignId('produk_bank_id')
                    ->nullable()
                    ->constrained('produk_banks')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'produk_bank_id')) {
                $table->dropConstrainedForeignId('produk_bank_id');
            }
        });
    }
};
