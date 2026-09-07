<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes; // Tambahkan ini agar sesuai dengan migration

    protected $table = 'mapels';

    protected $primaryKey = 'id_mapel';

    protected $fillable = ['nama_mapel'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_mapel', 'id_mapel');
    }
}
