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
        'jml_hadir' => 'integer',
        'jml_tidak_hadir' => 'integer',
    ];

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Guru Pengajar
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // Relasi ke User Pengisi/Pembuat (Sekretaris / Piket)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Jam Pelajaran Mulai
    public function jamMulai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_mulai', 'id_jam');
    }

    // Relasi ke Jam Pelajaran Selesai
    public function jamSelesai()
    {
        return $this->belongsTo(JamPel::class, 'id_jam_selesai', 'id_jam');
    }

    // Relasi ke Detail Absensi (Siswa yang hadir/sakit/izin/alpa di jurnal ini)
    public function details()
    {
        return $this->hasMany(DetailAbsensi::class, 'id_jurnal', 'id_jurnal');
    }
}
