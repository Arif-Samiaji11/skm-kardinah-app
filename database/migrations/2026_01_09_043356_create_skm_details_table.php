<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skm_details', function (Blueprint $table) {
            $table->id();

            // FK ke ruangan_periodes
            $table->foreignId('ruangan_periode_id')
                ->constrained('ruangan_periodes')
                ->cascadeOnDelete();

            // U1 - U9 (anggap nilai 1-5, bisa kamu ubah nanti)
            $table->unsignedTinyInteger('u1');
            $table->unsignedTinyInteger('u2');
            $table->unsignedTinyInteger('u3');
            $table->unsignedTinyInteger('u4');
            $table->unsignedTinyInteger('u5');
            $table->unsignedTinyInteger('u6');
            $table->unsignedTinyInteger('u7');
            $table->unsignedTinyInteger('u8');
            $table->unsignedTinyInteger('u9');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skm_details');
    }
};
