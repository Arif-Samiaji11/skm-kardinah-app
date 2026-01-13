<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nama_ruangan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_ruangan_id')
                ->constrained('unit_ruangan')
                ->cascadeOnDelete();

            $table->string('nama_ruangan', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nama_ruangan');
    }
};
