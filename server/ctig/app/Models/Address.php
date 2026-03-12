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

    public function organisation(): BelongsTo{
        return $this->belongsTo(Organization::class,'organization_id');
    }
    protected $fillable = [
        'is_active',
        'address',
        'organization_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function exams(): HasMany{
        return $this->hasMany(Exam::class, 'address_id');
    }
}
