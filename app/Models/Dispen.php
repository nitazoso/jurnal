<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispen extends Model
{
    protected $table = 'dispens';

    protected $primaryKey = 'id_dispen';

    protected $fillable = [
        'id_siswa',
        'id_kesiswaan',
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


    // =========================
    // SISWA
    // =========================

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function petugasKesiswaan()
    {
        return $this->belongsTo(User::class, 'id_kesiswaan', 'id_user');
    }


    // =========================
    // JAM MULAI
    // =========================

    public function jamMulai()
    {
        return $this->belongsTo(
            JamPel::class,
            'id_jam_mulai',
            'id_jam'
        );
    }


    // =========================
    // JAM SELESAI
    // =========================

    public function jamSelesai()
    {
        return $this->belongsTo(
            JamPel::class,
            'id_jam_selesai',
            'id_jam'
        );
    }


    // =========================
    // PETUGAS KESISWAAN YANG MENGONFIRMASI
    // =========================

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'disetujui_oleh',
            'id_user'
        );
    }
}
