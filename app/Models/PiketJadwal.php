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
        'tanggal',
        'shift',
        'jam_mulai',
        'jam_selesai',
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
}
