<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskVariant extends Model
{

    protected $fillable = [
        'fipi_guid',
        'content',
        'task_id',
        'is_active',
        'group_id',
        'mark'
    ];

    public function answers() : HasMany{
        return $this->hasMany(Answer::class, 'task_variant_id');
    }

    public function task(): BelongsTo{
        return $this->belongsTo( Task::class, "task_id");
    }
}
