<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan FK sementara (aman untuk truncate)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            RuanganSeeder::class, // isi unit_ruangan dulu
            AdminSeeder::class,
            UserSeeder::class,    // bikin user berdasarkan unit_ruangan
            LayananSeeder::class,
        ]);

        // Aktifkan kembali FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
