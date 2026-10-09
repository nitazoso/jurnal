<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruQrAttendance extends Model
{
    protected $table = 'guru_qr_attendances';

    protected $primaryKey = 'id_kehadiran_qr';

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_kelas',
        'tanggal',
        'discan_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'discan_pada' => 'datetime',
    ];
}