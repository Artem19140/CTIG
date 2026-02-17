<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subblock extends Model
{
    protected $fillable = [
        'name',
        'block_id',
        'is_active',
        'min_mark',
        'order'
    ];

    public function tasks():HasMany{
        return $this->hasMany(Task::class, 'subblock_id');    
    }

    public function block(): BelongsTo  {
        return $this->belongsTo( Block::class, "block_id");
    }
}
