<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\SkmCalculator;

class SkmDetail extends Model
{
    protected $table = 'skm_details';

    protected $fillable = [
        'skm_id',
        'no_urut',
        'u1','u2','u3','u4','u5','u6','u7','u8','u9',
    ];

    protected static function booted()
    {
        // ✅ Tetap: auto no_urut per skm_id (kode asli kamu, tidak dirusak)
        static::creating(function ($detail) {
            if (!isset($detail->no_urut) || $detail->no_urut === null) {
                $next = DB::table('skm_details')
                    ->where('skm_id', $detail->skm_id)
                    ->max('no_urut');

                $detail->no_urut = $next ? ($next + 1) : 1;
            }
        });

        // ✅ FULL OTOMATIS: setiap ada perubahan detail, hitung ulang & simpan IKM ke parent (skm)
        static::created(function ($detail) {
            SkmCalculator::recalculateAndPersist((int) $detail->skm_id);
        });

        static::updated(function ($detail) {
            SkmCalculator::recalculateAndPersist((int) $detail->skm_id);
        });

        static::deleted(function ($detail) {
            SkmCalculator::recalculateAndPersist((int) $detail->skm_id);
        });
    }

    // (opsional, tapi bagus) relasi ke parent SKM
    public function skm()
    {
        return $this->belongsTo(RuanganPeriode::class, 'skm_id');
    }
}
