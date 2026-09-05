<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAbsensi extends Model
{
    // Nama tabel disesuaikan dengan schema migration
    protected $table = 'detail_absensi';
    protected $primaryKey = 'id_absensi';
    
    protected $fillable = [
        'id_jurnal',
        'id_siswa',
        'status',
        'keterangan',
    ];


    // Relasi balik ke Jurnal
    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'id_jurnal', 'id_jurnal');
    }

    // Relasi ke Siswa (mengecek siapa siswa yang sakit/izin/alpha/dispen)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}