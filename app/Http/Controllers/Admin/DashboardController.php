<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitRuangan;
use App\Models\RuanganPeriode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // Ambil filter aktif
        // =========================
        $unitAktif = $request->query('unit');        // unit_ruangan_id (nullable)
        $ruanganAktif = $request->query('ruangan');  // nama_ruangan (nullable)
        $tahunAktif = (int) ($request->query('tahun') ?? now()->year);

        // =========================
        // Dropdown Unit
        // =========================
        $unitList = UnitRuangan::query()
            ->orderBy('nama_unit')
            ->get();

        // =========================
        // Base query (untuk ringkasan & chart) + filter unit/ruangan
        // =========================
        $baseQuery = RuanganPeriode::query();

        if (!empty($unitAktif)) {
            $baseQuery->where('unit_ruangan_id', $unitAktif);
        }

        if (!empty($ruanganAktif)) {
            $baseQuery->where('nama_ruangan', $ruanganAktif);
        }

        // =========================
        // Dropdown Ruangan (distinct) mengikuti filter unit (tapi tidak terpengaruh filter ruangan)
        // =========================
        $ruanganQuery = RuanganPeriode::query();

        if (!empty($unitAktif)) {
            $ruanganQuery->where('unit_ruangan_id', $unitAktif);
        }

        $ruanganList = $ruanganQuery
            ->select('nama_ruangan')
            ->whereNotNull('nama_ruangan')
            ->where('nama_ruangan', '!=', '')
            ->distinct()
            ->orderBy('nama_ruangan')
            ->pluck('nama_ruangan')
            ->values();

        // =========================
        // Ringkasan
        // =========================
        $totalUnit = UnitRuangan::count();

        $totalRuangan = (clone $baseQuery)
            ->select('nama_ruangan')
            ->distinct()
            ->count();

        $totalResponden = (clone $baseQuery)->sum('total_respon');

        // =========================
        // Tahun list (opsional)
        // =========================
        $tahunList = RuanganPeriode::query()
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->values();

        if ($tahunList->isEmpty()) {
            $tahunList = collect([$tahunAktif]);
        }

        // =========================
        // Data chart: IKM + kategori per ruangan per bulan (tahun aktif)
        // =========================
        $rows = (clone $baseQuery)
            ->where('tahun', $tahunAktif)
            ->select('nama_ruangan', 'bulan', 'ikm', 'kategori_ikm')
            ->orderBy('nama_ruangan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $chartData = $rows
            ->groupBy('nama_ruangan')
            ->map(function ($items, $namaRuangan) use ($bulanLabels) {
                $byBulan = $items->keyBy('bulan');

                $labels = [];
                $values = [];
                $categories = [];

                foreach ($bulanLabels as $bulan => $label) {
                    $labels[] = $label;

                    $row = $byBulan->get($bulan);
                    $values[] = $row?->ikm; // bisa null
                    $categories[] = $row?->kategori_ikm; // bisa null
                }

                return [
                    'nama_ruangan' => $namaRuangan,
                    'labels' => $labels,
                    'values' => $values,
                    'categories' => $categories,
                    'hash' => md5($namaRuangan),
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'unitList',
            'ruanganList',
            'unitAktif',
            'ruanganAktif',
            'totalUnit',
            'totalRuangan',
            'totalResponden',
            'tahunList',
            'tahunAktif',
            'chartData'
        ));
    }
}
