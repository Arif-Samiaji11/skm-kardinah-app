<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            if (!Schema::hasColumn('skm', 'ikm')) {
                $table->decimal('ikm', 6, 2)->nullable()->after('tahun');
            }

            if (!Schema::hasColumn('skm', 'nrr_total')) {
                $table->decimal('nrr_total', 8, 4)->nullable()->after('ikm');
            }

            if (!Schema::hasColumn('skm', 'total_respon')) {
                $table->integer('total_respon')->default(0)->after('nrr_total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            if (Schema::hasColumn('skm', 'total_respon')) {
                $table->dropColumn('total_respon');
            }
            if (Schema::hasColumn('skm', 'nrr_total')) {
                $table->dropColumn('nrr_total');
            }

            // ⚠️ ikm jangan di-drop kalau dari awal sudah ada
            // Jadi kita drop hanya kalau kamu yakin kolom ikm dibuat oleh migration ini.
            // Kalau kamu tidak yakin, BIARKAN SAJA (lebih aman).
        });
    }
};
