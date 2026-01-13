<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // rename table
        if (Schema::hasTable('layanan') && !Schema::hasTable('nama_ruangan')) {
            Schema::rename('layanan', 'nama_ruangan');
        }

        // rename columns + rebuild foreign key safely
        Schema::table('nama_ruangan', function (Blueprint $table) {
            // rename kolom ruangan_id -> unit_ruangan_id
            if (Schema::hasColumn('nama_ruangan', 'ruangan_id') && !Schema::hasColumn('nama_ruangan', 'unit_ruangan_id')) {
                $table->renameColumn('ruangan_id', 'unit_ruangan_id');
            }

            // rename kolom nama_layanan -> nama_ruangan
            if (Schema::hasColumn('nama_ruangan', 'nama_layanan') && !Schema::hasColumn('nama_ruangan', 'nama_ruangan')) {
                $table->renameColumn('nama_layanan', 'nama_ruangan');
            }
        });

        /**
         * Foreign key: kita drop constraint lama dulu (nama constraint bisa beda-beda),
         * lalu bikin ulang constraint baru.
         */
        // drop FK lama (MySQL default: layanan_ruangan_id_foreign)
        // karena table sudah rename jadi nama_ruangan, biasanya berubah jadi: nama_ruangan_ruangan_id_foreign
        // kita coba drop dengan beberapa kemungkinan nama.
        $possibleFks = [
            'layanan_ruangan_id_foreign',
            'nama_ruangan_ruangan_id_foreign',
            'nama_ruangan_unit_ruangan_id_foreign',
        ];

        foreach ($possibleFks as $fk) {
            try {
                Schema::table('nama_ruangan', function (Blueprint $table) use ($fk) {
                    $table->dropForeign($fk);
                });
            } catch (\Throwable $e) {
                // abaikan kalau tidak ada
            }
        }

        // create FK baru ke unit_ruangan(id)
        Schema::table('nama_ruangan', function (Blueprint $table) {
            // pastikan tidak double FK
            $table->foreign('unit_ruangan_id')
                ->references('id')
                ->on('unit_ruangan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // drop FK baru
        try {
            Schema::table('nama_ruangan', function (Blueprint $table) {
                $table->dropForeign(['unit_ruangan_id']);
            });
        } catch (\Throwable $e) {}

        // rename kolom balik
        Schema::table('nama_ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('nama_ruangan', 'unit_ruangan_id') && !Schema::hasColumn('nama_ruangan', 'ruangan_id')) {
                $table->renameColumn('unit_ruangan_id', 'ruangan_id');
            }

            if (Schema::hasColumn('nama_ruangan', 'nama_ruangan') && !Schema::hasColumn('nama_ruangan', 'nama_layanan')) {
                $table->renameColumn('nama_ruangan', 'nama_layanan');
            }
        });

        // rename table balik
        if (Schema::hasTable('nama_ruangan') && !Schema::hasTable('layanan')) {
            Schema::rename('nama_ruangan', 'layanan');
        }

        // (opsional) buat FK lama balik kalau kamu mau
    }
};
