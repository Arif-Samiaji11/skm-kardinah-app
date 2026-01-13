<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitRuangan;
use App\Models\RuanganPeriode;
use App\Models\SkmDetail;
use Illuminate\Http\Request;

class DetailSkmController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $units = UnitRuangan::with(['skmPeriode' => function ($q) use ($bulan, $tahun) {

            if (!empty($bulan)) {
                $q->where('bulan', $bulan);
            }

            if (!empty($tahun)) {
                $q->where('tahun', $tahun);
            }

            $q->orderBy('tahun', 'desc')
              ->orderBy('bulan', 'desc');

        }])
        ->orderBy('nama_unit')
        ->get();

        return view('admin.detail-skm.index', compact('units'));
    }

    /**
     * Halaman detail (tampilan persis preview) untuk 1 data SKM (RuanganPeriode).
     * URL: /admin/detail-skm/{ruangan_periode}
     */
    public function show(RuanganPeriode $ruangan_periode)
    {
        $parent = $ruangan_periode;

        // Ambil semua detail berdasarkan foreign key skm_id
        $details = SkmDetail::where('skm_id', $parent->id)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.detail-skm.show', compact('parent', 'details'));
    }
}
