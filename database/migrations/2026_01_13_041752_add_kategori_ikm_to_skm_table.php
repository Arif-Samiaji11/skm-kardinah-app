<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            // kalau kolom belum ada, tambahkan
            if (!Schema::hasColumn('skm', 'kategori_ikm')) {
                $table->string('kategori_ikm', 30)->nullable()->after('ikm');
            }
        });

        /**
         * ✅ Backfill otomatis dari kolom ikm (untuk data lama)
         * Kategori mengikuti aturan yang kamu pakai di view.
         */
        DB::table('skm')
            ->whereNotNull('ikm')
            ->update([
                'kategori_ikm' => DB::raw("
                    CASE
                        WHEN ikm >= 88.31 AND ikm <= 100.00 THEN 'SANGAT BAIK'
                        WHEN ikm >= 76.61 AND ikm <= 88.30 THEN 'BAIK'
                        WHEN ikm >= 65.00 AND ikm <= 76.60 THEN 'KURANG BAIK'
                        WHEN ikm >= 25.00 AND ikm <= 64.99 THEN 'TIDAK BAIK'
                        ELSE NULL
                    END
                ")
            ]);
    }

    public function down(): void
    {
        Schema::table('skm', function (Blueprint $table) {
            if (Schema::hasColumn('skm', 'kategori_ikm')) {
                $table->dropColumn('kategori_ikm');
            }
        });
    }
};
