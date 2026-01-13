<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // pastikan tabel lama ada & tabel baru belum ada
        if (Schema::hasTable('ruangan_periodes') && !Schema::hasTable('skm')) {
            Schema::rename('ruangan_periodes', 'skm');
        }
    }

    public function down(): void
    {
        // rollback: kembalikan ke nama lama
        if (Schema::hasTable('skm') && !Schema::hasTable('ruangan_periodes')) {
            Schema::rename('skm', 'ruangan_periodes');
        }
    }
};
