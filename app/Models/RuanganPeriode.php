<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuanganPeriode extends Model
{
    protected $table = 'skm';

    protected $fillable = [
        'unit_ruangan_id',
        'nama_ruangan',
        'bulan',
        'tahun',

        // ✅ hasil perhitungan otomatis (disimpan ke tabel skm)
        'ikm',
        'kategori_ikm', // ✅ NEW (disimpan ke DB)
        'nrr_total',
        'total_respon',
    ];

    public function unitRuangan()
    {
        return $this->belongsTo(UnitRuangan::class, 'unit_ruangan_id');
    }

    public function details()
    {
        return $this->hasMany(SkmDetail::class, 'skm_id');
    }

    /**
     * ✅ Helper kategori:
     * - Jika kategori_ikm di DB sudah ada, pakai itu.
     * - Jika belum ada, hitung dari ikm (fallback).
     */
    public function getKategoriIkmAttribute(): string
    {
        // kalau DB sudah menyimpan, pakai itu
        if (!empty($this->attributes['kategori_ikm'] ?? null)) {
            return (string) $this->attributes['kategori_ikm'];
        }

        // fallback hitung dari IKM
        $nilai = $this->ikm;

        if ($nilai === null) return '-';
        if ($nilai >= 88.31 && $nilai <= 100.00) return 'SANGAT BAIK';
        if ($nilai >= 76.61 && $nilai <= 88.30)  return 'BAIK';
        if ($nilai >= 65.00 && $nilai <= 76.60)  return 'KURANG BAIK';
        if ($nilai >= 25.00 && $nilai <= 64.99)  return 'TIDAK BAIK';

        return '-';
    }
}
