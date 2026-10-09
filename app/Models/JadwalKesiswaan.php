<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKesiswaan extends Model
{
    protected $table = 'jadwal_kesiswaans';

    protected $primaryKey = 'id_jadwal_kesiswaan';

    protected $fillable = [
        'id_user',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
