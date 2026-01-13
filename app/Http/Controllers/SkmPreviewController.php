<?php

namespace App\Http\Controllers;

use App\Models\RuanganPeriode;
use Illuminate\Http\Request;

class SkmPreviewController extends Controller
{
    public function show(RuanganPeriode $ruangan_periode)
    {
        $parent = $ruangan_periode;

        // Ambil semua detail untuk SKM ini
        $details = $parent->details()
            ->orderByDesc('id')
            ->get();

        // (Optional) hitung ringkasan nilai
        $summary = [
            'total_respon' => $details->count(),
            'avg_total' => null,
            'avg_u' => [],
        ];

        if ($summary['total_respon'] > 0) {
            // Rata-rata tiap U1..U9
            for ($i = 1; $i <= 9; $i++) {
                $col = 'u' . $i;
                $summary['avg_u'][$col] = round($details->avg($col), 2);
            }

            // Rata-rata total semua jawaban
            $allValues = [];
            foreach ($details as $d) {
                for ($i = 1; $i <= 9; $i++) {
                    $allValues[] = (int) $d->{'u' . $i};
                }
            }
            $summary['avg_total'] = round(array_sum($allValues) / max(count($allValues), 1), 2);
        }

        return view('skm_preview.show', compact('parent', 'details', 'summary'));
    }
}
