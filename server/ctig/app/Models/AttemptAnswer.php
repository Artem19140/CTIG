<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $fillable = [
        'exam_id', //Основной поиск по этому полю
        'task_variant_id',
        'attempt_id', //Основной поиск по этому полю
        'student_id', //Еще поиск по этому полю в карточке студента чтобы отобразить(дополнительно)
        'mark',
        'answer',
        'checked_by_id',
        'is_checked',
        'answer_id'
    ];
    
    protected $casts = [
        'is_checked' => 'boolean',
        'answer' => 'array'
    ];

    public function answer(): BelongsTo{
        return $this->belongsTo(Answer::class, 'answer_id');
    }

    public function attempt(): BelongsTo{
        return $this->belongsTo(Attempt::class, 'attempt_id');
    }

    public function taskVariant(): BelongsTo{
        return $this->belongsTo(TaskVariant::class, 'task_variant_id');
    }

}
