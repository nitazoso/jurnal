<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PiketJadwal extends Model
{
    use SoftDeletes;

    protected $table = 'piket_jadwals';

    protected $primaryKey = 'id_piket_jadwal';

    protected $fillable = [
        'id_guru',
        'petugas_kbm_pagi_id',
        'koordinator_kbm_pagi_id',
        'petugas_kbm_siang_id',
        'koordinator_kbm_siang_id',
        'piket_waka_id',
        'tanggal',
        'shift',
        'jam_mulai',
        'jam_selesai',
        'jam_mulai_kbm_pagi',
        'jam_selesai_kbm_pagi',
        'jam_mulai_koordinator_pagi',
        'jam_selesai_koordinator_pagi',
        'jam_mulai_kbm_siang',
        'jam_selesai_kbm_siang',
        'jam_mulai_koordinator_siang',
        'jam_selesai_koordinator_siang',
        'jenis_tugas',
        'posisi',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function petugasKbmPagi() { return $this->belongsTo(Guru::class, 'petugas_kbm_pagi_id', 'id_guru'); }
    public function koordinatorKbmPagi() { return $this->belongsTo(Guru::class, 'koordinator_kbm_pagi_id', 'id_guru'); }
    public function petugasKbmSiang() { return $this->belongsTo(Guru::class, 'petugas_kbm_siang_id', 'id_guru'); }
    public function koordinatorKbmSiang() { return $this->belongsTo(Guru::class, 'koordinator_kbm_siang_id', 'id_guru'); }
    public function piketWaka() { return $this->belongsTo(Guru::class, 'piket_waka_id', 'id_guru'); }
}
