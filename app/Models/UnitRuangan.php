<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitRuangan extends Model
{
    protected $table = 'unit_ruangan';

    protected $fillable = [
        'nama_unit',
    ];

    public function skmPeriode()
    {
        return $this->hasMany(RuanganPeriode::class, 'unit_ruangan_id');
    }
}
