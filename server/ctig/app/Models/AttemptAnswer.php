<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $table = 'attempt_answers';
    protected $fillable = [
        'exam_id', 
        'task_variant_id',
        'attempt_id', 
        'foreign_national_id',
        'mark',
        'answer',
        'checked_by_id',
        'is_checked',
        'answer_id',
        'checked_at',
        'audio_played'
    ];
    
    protected $casts = [
        'is_checked' => 'boolean',
        'answer' => 'array',
        'checked_at' => 'datetime',
        'audio_played' => 'boolean'
    ];

    public function answer(): BelongsTo{
        return $this->belongsTo(Answer::class, 'answer_id');
    }

    public function isChecked():bool{
        return $this->is_checked;
    }

    public function attempt(): BelongsTo{
        return $this->belongsTo(Attempt::class, 'attempt_id');
    }

    public function taskVariant(): BelongsTo{
        return $this->belongsTo(TaskVariant::class, 'task_variant_id');
    }

    public function scopeNotChecked(Builder $query){
        return $query->where('is_checked', false);
    }

}
