<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamPel extends Model
{
    protected $table = 'jam_pels';

    protected $primaryKey = 'id_jam';

    protected $fillable = [
        'klp_hari',
        'jam_ke',
        'jenis',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'jam_ke' => 'integer',
    ];

    // Relasi ke Jadwal (sebagai jam mulai)
    public function jadwalsMulai()
    {
        return $this->hasMany(Jadwal::class, 'id_jam_mulai', 'id_jam');
    }

    // Relasi ke Jadwal (sebagai jam selesai)
    public function jadwalsSelesai()
    {
        return $this->hasMany(Jadwal::class, 'id_jam_selesai', 'id_jam');
    }

    // Relasi ke Jurnal (sebagai jam mulai)
    public function jurnalsMulai()
    {
        return $this->hasMany(Jurnal::class, 'id_jam_mulai', 'id_jam');
    }

    // Relasi ke Jurnal (sebagai jam selesai)
    public function jurnalsSelesai()
    {
        return $this->hasMany(Jurnal::class, 'id_jam_selesai', 'id_jam');
    }
}
