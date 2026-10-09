<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;

    protected $table = 'mapels';

    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'nama_mapel',
    ];
}