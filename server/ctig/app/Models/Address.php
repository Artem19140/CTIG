<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;
    protected $fillable = [
        'is_active',
        'address'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function isActual():bool{
        return $this->is_active;
    }

    public function exams(): HasMany{
        return $this->hasMany(Exam::class, 'address_id');
    }
}
