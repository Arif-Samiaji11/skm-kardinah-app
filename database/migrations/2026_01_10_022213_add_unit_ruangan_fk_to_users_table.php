<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan kedua tabel ada
        if (!Schema::hasTable('users') || !Schema::hasTable('unit_ruangan')) {
            return;
        }

        // Pastikan kolom unit_ruangan_id ada
        if (!Schema::hasColumn('users', 'unit_ruangan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('unit_ruangan_id')->nullable()->index();
            });
        }

        // Cek apakah foreign key untuk users.unit_ruangan_id sudah ada
        $dbName = DB::getDatabaseName();
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $dbName)
            ->where('TABLE_NAME', 'users')
            ->where('COLUMN_NAME', 'unit_ruangan_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        // Kalau FK ada, drop dulu (biar aman saat rerun)
        if ($fkName) {
            DB::statement("ALTER TABLE `users` DROP FOREIGN KEY `$fkName`");
        }

        // Tambah FK baru
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('unit_ruangan_id')
                ->references('id')
                ->on('unit_ruangan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        // Cek FK name yang terpasang (kalau ada)
        $dbName = DB::getDatabaseName();
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $dbName)
            ->where('TABLE_NAME', 'users')
            ->where('COLUMN_NAME', 'unit_ruangan_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fkName) {
            DB::statement("ALTER TABLE `users` DROP FOREIGN KEY `$fkName`");
        }
    }
};
