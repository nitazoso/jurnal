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

    public function waliKelas()
    {
        return $this->belongsTo(
            Guru::class,
            'wali_kelas',
            'id_guru'
        );
    }

    public function siswas()
    {
        return $this->hasMany(
            Siswa::class,
            'id_kelas',
            'id_kelas'
        );
    }
}
