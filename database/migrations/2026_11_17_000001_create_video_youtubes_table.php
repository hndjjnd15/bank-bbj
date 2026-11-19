<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_youtubes', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();

            $table->index(['diterbitkan', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_youtubes');
    }
};
