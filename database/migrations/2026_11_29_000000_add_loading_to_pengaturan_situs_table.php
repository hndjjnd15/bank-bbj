<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_situs', function (Blueprint $table) {
            $table->boolean('loading_aktif')->default(false)->after('whatsapp_aktif');
            $table->string('loading_gif')->nullable()->after('loading_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_situs', function (Blueprint $table) {
            $table->dropColumn(['loading_aktif', 'loading_gif']);
        });
    }
};
