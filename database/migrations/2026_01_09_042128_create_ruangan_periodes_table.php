<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan', 150);
            $table->unsignedTinyInteger('bulan');   // 1-12
            $table->unsignedSmallInteger('tahun');  // 1900-2100 aman
            $table->timestamps();

            $table->unique(['nama_ruangan', 'bulan', 'tahun'], 'uniq_ruangan_bulan_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan_periodes');
    }
};
