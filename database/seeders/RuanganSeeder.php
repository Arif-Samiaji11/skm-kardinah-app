<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel tujuan ada
        if (!DB::getSchemaBuilder()->hasTable('unit_ruangan')) {
            return;
        }

        // Kosongkan tabel unit_ruangan
        DB::table('unit_ruangan')->truncate();

        // DATA UNIT RUANGAN
        DB::table('unit_ruangan')->insert([
            [
                'nama_unit' => 'Intensive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_unit' => 'Rawat Inap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_unit' => 'Rawat Jalan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_unit' => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
