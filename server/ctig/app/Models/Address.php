<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    public function center(): BelongsTo{
        return $this->belongsTo(Center::class,'center_id');
    }
    protected $fillable = [
        'is_active',
        'address',
        'center_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function exams(): HasMany{
        return $this->hasMany(Exam::class, 'address_id');
    }
}
