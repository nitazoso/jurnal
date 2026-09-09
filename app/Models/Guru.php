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
}