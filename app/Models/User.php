<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements PasskeyUser
{
    use HasFactory, Notifiable, PasskeyAuthenticatable, SoftDeletes, TwoFactorAuthenticatable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'password',
        'nama_user',
        'role',
        'id_guru',
        'id_kelas',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasPiketToday(): bool
    {
        if (! $this->id_guru) {
            return false;
        }

        return \App\Models\PiketJadwal::where(function ($query) {
            $query->where('id_guru', $this->id_guru)
                ->orWhere('petugas_kbm_pagi_id', $this->id_guru)
                ->orWhere('koordinator_kbm_pagi_id', $this->id_guru)
                ->orWhere('petugas_kbm_siang_id', $this->id_guru)
                ->orWhere('koordinator_kbm_siang_id', $this->id_guru)
                ->orWhere('piket_waka_id', $this->id_guru);
        })
            ->whereDate('tanggal', now()->toDateString())
            ->exists();
    }

    public function initials(): string
    {
        $initials = Str::initials($this->nama_user ?? '', true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'id_user', 'id_user');
    }
}
