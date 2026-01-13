<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel tujuan ada
        if (!DB::getSchemaBuilder()->hasTable('nama_ruangan')) {
            return;
        }

        // Kosongkan tabel nama_ruangan (hasil rename dari layanan)
        DB::table('nama_ruangan')->truncate();

        // Ambil id unit_ruangan dengan key nama_unit (hasil rename dari ruangan + nama_ruangan -> nama_unit)
        $unitRuangan = DB::table('unit_ruangan')
            ->pluck('id', 'nama_unit')
            ->toArray();

        // Helper aman: ambil id by nama_unit, kalau tidak ada return null
        $id = fn(string $namaUnit) => $unitRuangan[$namaUnit] ?? null;

        $data = [

            // ================= INTENSIVE =================
            ['unit_ruangan_id' => $id('Intensive'), 'nama_ruangan' => 'ICU'],
            ['unit_ruangan_id' => $id('Intensive'), 'nama_ruangan' => 'ICVCU'],
            ['unit_ruangan_id' => $id('Intensive'), 'nama_ruangan' => 'PICU-NICU'],

            // ================= RAWAT INAP =================
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Cendana 1'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Cendana 2'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Cendana 3 Anak'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Dahlia'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Dewadaru'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Edelweis Atas'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Edelweis Bawah'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Lavender Atas'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Lavender Bawah'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Mawar'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Niscala 4'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Niscala 5'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Puspanidra'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Rosella'],
            ['unit_ruangan_id' => $id('Rawat Inap'), 'nama_ruangan' => 'Wijaya Kusuma Bawah'],

            // ================= RAWAT JALAN =================
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Anak'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Bedah Anak'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Anak Bedah 2026'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Bedah Sarah'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Bedah Umum'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Dewadaru'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Endokrin'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Gigi dan Bedah Mulut'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Jantung'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Jiwa'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Kebidanan'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Kulit'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Mata'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Orthopedi'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Paru'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Penyakit Dalam'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Psikologi'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Saraf'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli TB MDR'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli THT'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli Urologi'],
            ['unit_ruangan_id' => $id('Rawat Jalan'), 'nama_ruangan' => 'Poli VCT'],

            // ================= UMUM =================
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Farmasi Rajal dan Dewadaru'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Haemodialisa'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'IGD PONEK'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Laboratorium'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Loket Pembayaran'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Radiologi'],
            ['unit_ruangan_id' => $id('Umum'), 'nama_ruangan' => 'Rehabilitasi Medik'],
        ];

        // Buang yang unit_ruangan_id null (kalau ada nama unit tidak ditemukan)
        $data = array_values(array_filter($data, fn($row) => !is_null($row['unit_ruangan_id'])));

        DB::table('nama_ruangan')->insert($data);
    }
}
