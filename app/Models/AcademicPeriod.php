<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $table = 'academic_periods';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
    ];

    public static function current(): ?self
    {
        return static::query()->find(1);
    }
}