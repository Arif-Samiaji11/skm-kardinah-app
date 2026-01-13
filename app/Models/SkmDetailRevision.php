<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkmDetailRevision extends Model
{
    protected $table = 'skm_detail_revisions';

    protected $fillable = [
        'skm_detail_id',
        'u1','u2','u3','u4','u5','u6','u7','u8','u9',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(SkmDetail::class, 'skm_detail_id');
    }
}
