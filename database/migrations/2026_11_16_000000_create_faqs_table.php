<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('halaman');
            $table->string('tanya');
            $table->text('jawab');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('diterbitkan')->default(true);
            $table->timestamps();

            $table->index(['halaman', 'diterbitkan', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
