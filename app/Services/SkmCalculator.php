<?php

namespace App\Services;

use App\Models\RuanganPeriode;
use App\Models\SkmDetail;

class SkmCalculator
{
    /**
     * Hitung ulang IKM untuk 1 SKM dan simpan ke database
     */
    public static function recalculateAndPersist(int $skmId): void
    {
        $count = SkmDetail::where('skm_id', $skmId)->count();

        // Jika belum ada respon
        if ($count === 0) {
            RuanganPeriode::where('id', $skmId)->update([
                'ikm' => null,
                'nrr_total' => null,
                'total_respon' => 0,
            ]);
            return;
        }

        $bobot = 1 / 9;

        // Ambil total nilai per unsur
        $sum = SkmDetail::where('skm_id', $skmId)->selectRaw('
            SUM(u1) as u1, SUM(u2) as u2, SUM(u3) as u3,
            SUM(u4) as u4, SUM(u5) as u5, SUM(u6) as u6,
            SUM(u7) as u7, SUM(u8) as u8, SUM(u9) as u9
        ')->first();

        $sumNrrTertimbang = 0;

        for ($i = 1; $i <= 9; $i++) {
            $col = "u{$i}";
            $nrr = round(((float) $sum->$col) / $count, 2);
            $sumNrrTertimbang += round($nrr * $bobot, 4);
        }

        $sumNrrTertimbang = round($sumNrrTertimbang, 4);
        $ikm = round($sumNrrTertimbang * 25, 2);

        // Simpan ke tabel skm
        RuanganPeriode::where('id', $skmId)->update([
            'ikm' => $ikm,
            'nrr_total' => $sumNrrTertimbang,
            'total_respon' => $count,
        ]);
    }
}
