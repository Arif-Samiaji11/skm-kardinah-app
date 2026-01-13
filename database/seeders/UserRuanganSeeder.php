<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            [
                'name'     => 'Ruangan Intensive 2026',
                'email'    => 'intensive@rsudkardinah.go.id',
                'ruangan'  => 'Intensive',
            ],
            [
                'name'     => 'Rawat Inap 2026',
                'email'    => 'rawatinap@rsudkardinah.go.id',
                'ruangan'  => 'Rawat Inap',
            ],
            [
                'name'     => 'Rawat Jalan 2026',
                'email'    => 'rawatjalan@rsudkardinah.go.id',
                'ruangan'  => 'Rawat Jalan',
            ],
            [
                'name'     => 'Umum 2026',
                'email'    => 'umum@rsudkardinah.go.id',
                'ruangan'  => 'Umum',
            ],
        ];

        foreach ($ruangans as $ruangan) {
            User::updateOrCreate(
                ['email' => $ruangan['email']],
                [
                    'name'     => $ruangan['name'],
                    'role'     => 'ruangan',
                    'ruangan'  => $ruangan['ruangan'],
                    'tahun'    => 2026,
                    'password' => Hash::make('ruangan2026'),
                ]
            );
        }
    }
}
