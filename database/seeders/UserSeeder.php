<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS USER LAMA (KECUALI ADMIN)
        DB::table('users')->where('role', 'ruangan')->delete();

        // Pastikan tabel unit_ruangan ada
        if (!DB::getSchemaBuilder()->hasTable('unit_ruangan')) {
            return;
        }

        // Ambil data dari unit_ruangan (hasil rename dari ruangan)
        $unitRuangan = DB::table('unit_ruangan')->get();

        foreach ($unitRuangan as $u) {
            $nama = $u->nama_unit;

            // Buat email: hilangkan spasi + lowercase
            $emailPrefix = strtolower(str_replace(' ', '', $nama));

            DB::table('users')->insert([
                'name'            => $nama . ' 2026',
                'email'           => $emailPrefix . '@rsudkardinah.go.id',
                'role'            => 'ruangan',

                // kolom lama (biar kompatibel dengan code lama kamu)
                'ruangan'         => $nama,
                'tahun'           => 2026,

                // kolom baru (penting untuk filter berdasarkan login)
                'unit_ruangan_id' => $u->id,

                'password'        => Hash::make('ruangan2026'),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
