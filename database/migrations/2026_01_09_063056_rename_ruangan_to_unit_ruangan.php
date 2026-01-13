<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // rename table
        if (Schema::hasTable('ruangan') && !Schema::hasTable('unit_ruangan')) {
            Schema::rename('ruangan', 'unit_ruangan');
        }

        // rename column
        Schema::table('unit_ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('unit_ruangan', 'nama_ruangan') && !Schema::hasColumn('unit_ruangan', 'nama_unit')) {
                $table->renameColumn('nama_ruangan', 'nama_unit');
            }
        });
    }

    public function down(): void
    {
        // balikkan column
        Schema::table('unit_ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('unit_ruangan', 'nama_unit') && !Schema::hasColumn('unit_ruangan', 'nama_ruangan')) {
                $table->renameColumn('nama_unit', 'nama_ruangan');
            }
        });

        // balikkan table
        if (Schema::hasTable('unit_ruangan') && !Schema::hasTable('ruangan')) {
            Schema::rename('unit_ruangan', 'ruangan');
        }
    }
};
