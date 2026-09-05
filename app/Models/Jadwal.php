<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;

    protected $table = 'jadwals';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_guru',
        'id_mapel',
        'id_kelas',
        'id_jam_mulai',
        'id_jam_selesai',
        'hari',
        'semester',
        'tahun_ajaran',
    ];

    
    // Relasi ke Guru Pengajar
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Mata Pelajaran
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Jam Pelajaran Mulai (ke primary key id_jam di jam_pels)
    public function jamMulai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_mulai', 'id_jam');
    }

    // Relasi ke Jam Pelajaran Selesai (ke primary key id_jam di jam_pels)
    public function jamSelesai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_selesai', 'id_jam');
    }

    // Relasi ke Jurnal (Satu jadwal bisa punya banyak riwayat jurnal)
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'id_jadwal', 'id_jadwal');
    }
}