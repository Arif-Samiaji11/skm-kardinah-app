<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom unit_ruangan_id jika belum ada
        Schema::table('skm', function (Blueprint $table) {
            if (!Schema::hasColumn('skm', 'unit_ruangan_id')) {
                $table->foreignId('unit_ruangan_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('unit_ruangan')
                    ->nullOnDelete();
            }
        });

        /**
         * Isi otomatis data lama (opsional tapi bagus).
         * Logika:
         * - skm.nama_ruangan berisi contoh: ICU / Cendana 1 / Poli Anak
         * - Itu sebenarnya ada di tabel nama_ruangan.nama_ruangan
         * - nama_ruangan punya unit_ruangan_id
         * Jadi kita map:
         * skm.nama_ruangan -> nama_ruangan.nama_ruangan -> nama_ruangan.unit_ruangan_id -> skm.unit_ruangan_id
         */
        if (
            Schema::hasTable('nama_ruangan') &&
            Schema::hasColumn('skm', 'nama_ruangan') &&
            Schema::hasColumn('nama_ruangan', 'nama_ruangan') &&
            Schema::hasColumn('nama_ruangan', 'unit_ruangan_id')
        ) {
            DB::statement("
                UPDATE skm s
                JOIN nama_ruangan nr ON nr.nama_ruangan = s.nama_ruangan
                SET s.unit_ruangan_id = nr.unit_ruangan_id
                WHERE s.unit_ruangan_id IS NULL
            ");
        }
    }

    public function down(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            if (Schema::hasColumn('skm', 'unit_ruangan_id')) {
                try {
                    $table->dropForeign(['unit_ruangan_id']);
                } catch (\Throwable $e) {
                    // ignore
                }
                $table->dropColumn('unit_ruangan_id');
            }
        });
    }
};
