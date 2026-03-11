<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'director_fio',
        'certificates_issue_address',
        'is_work',
        'ogrn',
        'inn',
        'address',
        'name_genitive'
    ];

    protected $casts = [
        'is_work' => 'boolean'
    ];
}
