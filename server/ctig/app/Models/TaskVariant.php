<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskVariant extends Model
{

    protected $fillable = [
        'fipi_number',
        'content',
        'task_id',
        'is_active',
        'group_number',
        'mark'
    ];

    protected $casts = [
        'content' => 'array'
    ];


    public function answers() : HasMany{
        return $this->hasMany(Answer::class, 'task_variant_id');
    }

    public function task(): BelongsTo{
        return $this->belongsTo( Task::class, "task_id");
    }

    // public function attemptsAnswers(): HasMany{
    //     return $this->hasMany(AttemptAnswer::class, 'task_variant_id');
    // }

    public function attemptsAnswer(): HasOne{
        return $this->hasOne(AttemptAnswer::class, 'task_variant_id');
    }
}
