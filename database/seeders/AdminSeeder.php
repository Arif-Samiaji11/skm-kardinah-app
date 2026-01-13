<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@rsudkardinah.go.id'],
            [
                'name' => 'Admin SKM',
                'role' => 'admin',
                'ruangan' => null,
                'tahun' => 2026,
                'password' => Hash::make('admin2026'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
