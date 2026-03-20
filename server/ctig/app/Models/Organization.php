<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'director_fio',
        'certificates_issue_address',
        'is_active',
        'ogrn',
        'inn',
        'address',
        'name_genitive',
        'time_zone'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function users(): HasMany{
        return $this->hasMany(User::class, 'organization_id');
    }
}
