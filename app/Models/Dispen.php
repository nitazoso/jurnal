<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispen extends Model
{
    protected $table = 'dispens';

    protected $primaryKey = 'id_dispen';

    protected $fillable = [
        'id_siswa',
        'id_waka',
        'tanggal',
        'id_jam_mulai',
        'id_jam_selesai',
        'alasan',
        'status',
        'token_verifikasi',
        'verified_at',
        'verified_by',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'verified_at' => 'datetime',
    ];


    // =========================
    // SISWA
    // =========================

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }


    // =========================
    // WAKA
    // =========================

    public function waka()
    {
        return $this->belongsTo(
            Guru::class,
            'id_waka',
            'id_guru'
        );
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
    // USER VERIFIKATOR
    // =========================

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by',
            'id_user'
        );
    }
}