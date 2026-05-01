<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Center extends Model
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
        'time_zone',
        'commission_chairman'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function users(): HasMany{
        return $this->hasMany(User::class, 'center_id');
    }
    public function isActive():bool{
        return $this->is_active;
    }
}
