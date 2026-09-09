<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurnal extends Model
{
    use SoftDeletes;

    protected $table = 'jurnals';

    protected $primaryKey = 'id_jurnal';

    protected $fillable = [
        'id_jadwal',
        'id_kelas',
        'id_guru',
        'id_user',
        'id_jam_mulai',
        'id_jam_selesai',
        'tanggal',
        'materi',
        'status_guru',
        'ada_tugas',
        'deskripsi_tugas',
        'jml_hadir',
        'jml_tidak_hadir',
        'status_validasi_guru',
        'catatan_revisi',
        'catatan_umum',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}