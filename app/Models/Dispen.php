<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispen extends Model
{
    protected $table = 'dispens';

    protected $primaryKey = 'id_dispen';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'id_jam_mulai',
        'id_jam_selesai',
        'alasan',
        'status',
        'disetujui_oleh',
        'disetujui_pada',
        'catatan_persetujuan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function jamMulai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_mulai', 'id_jam');
    }

    public function jamSelesai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_selesai', 'id_jam');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id_user');
    }
}
