<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // pastikan tabel ada
        if (!Schema::hasTable('skm_details')) {
            return;
        }

        /**
         * 1) Drop foreign key lama yang mengarah ke ruangan_periodes
         *    Nama constraint bisa beda-beda, jadi kita coba beberapa kemungkinan.
         */
        $possibleFks = [
            'skm_details_ruangan_periode_id_foreign',
            'skm_details_ruangan_periodes_id_foreign', // just in case
            'ruangan_periode_id_foreign',              // just in case
        ];

        foreach ($possibleFks as $fk) {
            try {
                Schema::table('skm_details', function (Blueprint $table) use ($fk) {
                    $table->dropForeign($fk);
                });
            } catch (\Throwable $e) {
                // abaikan kalau tidak ada
            }
        }

        // 2) Kalau kolom lama ada, rename ke skm_id (lebih konsisten)
        if (Schema::hasColumn('skm_details', 'ruangan_periode_id') && !Schema::hasColumn('skm_details', 'skm_id')) {
            Schema::table('skm_details', function (Blueprint $table) {
                $table->renameColumn('ruangan_periode_id', 'skm_id');
            });
        }

        /**
         * 3) Pastikan tipe kolom sesuai dengan skm.id
         *    (umumnya unsignedBigInteger karena $table->id()).
         */
        Schema::table('skm_details', function (Blueprint $table) {
            if (Schema::hasColumn('skm_details', 'skm_id')) {
                // memastikan kolomnya unsignedBigInteger (jika sebelumnya sudah benar, aman)
                $table->unsignedBigInteger('skm_id')->change();
            }
        });

        /**
         * 4) Buat foreign key baru ke tabel skm(id)
         */
        Schema::table('skm_details', function (Blueprint $table) {
            if (Schema::hasColumn('skm_details', 'skm_id')) {
                $table->foreign('skm_id')
                    ->references('id')
                    ->on('skm')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('skm_details')) {
            return;
        }

        // Drop FK baru (skm_id -> skm)
        try {
            Schema::table('skm_details', function (Blueprint $table) {
                $table->dropForeign(['skm_id']);
            });
        } catch (\Throwable $e) {}

        // Rename balik skm_id -> ruangan_periode_id
        if (Schema::hasColumn('skm_details', 'skm_id') && !Schema::hasColumn('skm_details', 'ruangan_periode_id')) {
            Schema::table('skm_details', function (Blueprint $table) {
                $table->renameColumn('skm_id', 'ruangan_periode_id');
            });
        }

        // Buat FK lama balik ke ruangan_periodes kalau tabelnya masih ada
        if (Schema::hasTable('ruangan_periodes') && Schema::hasColumn('skm_details', 'ruangan_periode_id')) {
            Schema::table('skm_details', function (Blueprint $table) {
                $table->foreign('ruangan_periode_id')
                    ->references('id')
                    ->on('ruangan_periodes')
                    ->cascadeOnDelete();
            });
        }
    }
};
