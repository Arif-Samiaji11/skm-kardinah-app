<?php

namespace App\Http\Controllers;

use App\Models\RuanganPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganPeriodeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user?->unit_ruangan_id, 403, 'Akun kamu belum punya unit ruangan. Hubungi admin.');

        $rows = RuanganPeriode::where('unit_ruangan_id', $user->unit_ruangan_id)
            // ✅ Urut sesuai input: data pertama tampil paling atas
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('dashboard', compact('rows'));
    }

    public function create()
    {
        $user = auth()->user();
        abort_if(!$user?->unit_ruangan_id, 403, 'Akun kamu belum punya unit ruangan. Hubungi admin.');

        // Dropdown "nama_ruangan" dari tabel nama_ruangan sesuai unit ruangan user login
        $namaRuangan = DB::table('nama_ruangan')
            ->select('id', 'unit_ruangan_id', 'nama_ruangan')
            ->where('unit_ruangan_id', $user->unit_ruangan_id)
            ->orderBy('nama_ruangan')
            ->get();

        return view('ruangan_periode.create', compact('namaRuangan'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user?->unit_ruangan_id, 403, 'Akun kamu belum punya unit ruangan. Hubungi admin.');

        $validated = $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:150'],
            'bulan'        => ['required', 'integer', 'min:1', 'max:12'],
            'tahun'        => ['required', 'integer', 'min:1900', 'max:2100'],
        ]);

        /**
         * VALIDASI TAMBAHAN (ANTI NAKAL):
         * Pastikan nama_ruangan yang dikirim benar-benar milik unit ruangan user login.
         * Jadi walaupun user edit HTML / request, tetap ditolak.
         */
        $exists = DB::table('nama_ruangan')
            ->where('unit_ruangan_id', $user->unit_ruangan_id)
            ->where('nama_ruangan', $validated['nama_ruangan'])
            ->exists();

        abort_unless($exists, 403, 'Nama ruangan tidak valid untuk akun kamu.');

        // Paksa data mengikuti login
        $validated['unit_ruangan_id'] = $user->unit_ruangan_id;

        // Simpan ke tabel skm
        RuanganPeriode::create($validated);

        return redirect()
            ->route('ruangan-periode.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy(RuanganPeriode $ruangan_periode)
    {
        $user = auth()->user();
        abort_if(!$user?->unit_ruangan_id, 403, 'Akun kamu belum punya unit ruangan. Hubungi admin.');

        // Guard: hanya boleh hapus milik unit ruangan sendiri
        abort_unless((int) $ruangan_periode->unit_ruangan_id === (int) $user->unit_ruangan_id, 403);

        $ruangan_periode->delete();

        /**
         * Reset AUTO_INCREMENT hanya jika tabel kosong total.
         * Catatan: delete sebagian memang normalnya auto increment tidak "mundur".
         */
        if (RuanganPeriode::count() === 0) {
            DB::statement('ALTER TABLE skm AUTO_INCREMENT = 1');
        }

        // Optional reset auto increment skm_details kalau kosong total
        if (DB::getSchemaBuilder()->hasTable('skm_details')) {
            if (DB::table('skm_details')->count() === 0) {
                DB::statement('ALTER TABLE skm_details AUTO_INCREMENT = 1');
            }
        }

        return redirect()
            ->route('ruangan-periode.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    // Belum dipakai
    public function show(RuanganPeriode $ruangan_periode) {}
    public function edit(RuanganPeriode $ruangan_periode) {}
    public function update(Request $request, RuanganPeriode $ruangan_periode) {}
}
