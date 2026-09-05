<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'gurus';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nip',
        'nama_guru',
        'no_hp',
    ];

    // Relasi ke User (Jika guru ini punya akun login)
    public function user()
    {
        return $this->hasOne(User::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Kelas (Jika guru ini menjadi Wali Kelas)
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas', 'id_guru');
    }

    // Relasi ke Jadwal Mengajar
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Jurnal (Riwayat mengajar guru)
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'id_guru', 'id_guru');
    }
}