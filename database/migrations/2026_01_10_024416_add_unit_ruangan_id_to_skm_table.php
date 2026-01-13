<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) tambah kolom unit_ruangan_id kalau belum ada
        Schema::table('skm', function (Blueprint $table) {
            if (!Schema::hasColumn('skm', 'unit_ruangan_id')) {
                $table->foreignId('unit_ruangan_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('unit_ruangan')
                    ->nullOnDelete();
            }
        });

        // 2) opsional: isi data lama agar tidak null (ambil berdasarkan nama_ruangan yang sama dengan nama_unit)
        // Kalau nama_ruangan di skm = nama_unit di unit_ruangan, ini akan otomatis kepasang.
        if (Schema::hasTable('unit_ruangan') && Schema::hasColumn('skm', 'nama_ruangan')) {
            DB::statement("
                UPDATE skm s
                JOIN unit_ruangan u ON u.nama_unit = s.nama_ruangan
                SET s.unit_ruangan_id = u.id
                WHERE s.unit_ruangan_id IS NULL
            ");
        }
    }

    public function down(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            if (Schema::hasColumn('skm', 'unit_ruangan_id')) {
                // drop FK + kolom
                try {
                    $table->dropForeign(['unit_ruangan_id']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('unit_ruangan_id');
            }
        });
    }
};
