<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Answer extends Model
{
    protected $fillable = [
        'contain',
        'is_correct',
        'type',
        'creator_id',
        'answer_id',
        'task_id'
    ];

    public function task(): BelongsTo{
        return $this->belongsTo(related: Task::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo( User::class, "creator_id");
    }
}
