<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $primaryKey = 'id_mapel';
    protected $fillable = ['nama_mapel'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_mapel', 'id_mapel');
    }
}