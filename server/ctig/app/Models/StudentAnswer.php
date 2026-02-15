<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $fillable = [
        'exam_id', //Основной поиск по этому полю
        'exam_block_id',
        'task_variant_id',
        'exam_attempt_id', //Основной поиск по этому полю
        'student_id', //Еще поиск по этому полю в карточке студента чтобы отобразить(дополнительно)
        'mark',
        'student_answer',
        'checked_by_id'
    ];

    public function attempt(): BelongsTo{
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

}
