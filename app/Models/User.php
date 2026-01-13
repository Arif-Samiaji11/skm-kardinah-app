<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',

        'role',
        'ruangan',
        'tahun',

        'unit_ruangan_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function unitRuangan()
    {
        return $this->belongsTo(UnitRuangan::class, 'unit_ruangan_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
