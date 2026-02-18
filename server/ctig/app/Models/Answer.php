<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Answer extends Model
{
    protected $fillable = [
        'content',
        'is_correct',
        'task_variant_id',
        'order',
        'file_path'
    ];

    public function taskVariant(): BelongsTo{
        return $this->belongsTo( TaskVariant::class, 'task_variant_id');
    }
}
