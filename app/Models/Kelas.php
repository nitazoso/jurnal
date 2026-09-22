<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelases';

    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'nama_kelas',
        'qr_token',
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

    protected static function booted(): void
    {
        static::creating(function (Kelas $kelas) {
            $kelas->qr_token ??= Str::random(48);
        });
    }
}
