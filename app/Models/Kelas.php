<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelases';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas',
        'jumlah_siswa',
    ];

    protected $casts = [
        'jumlah_siswa' => 'integer',
    ];

    // Relasi ke Guru sebagai Wali Kelas
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas', 'id_guru');
    }

    // Relasi ke Siswa di kelas ini
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke User (Sekretaris kelas ini)
    public function users()
    {
        return $this->hasMany(User::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Jurnal
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'id_kelas', 'id_kelas');
    }
}