<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'exam_attempt_id',
        'finished_at' //Куда бы впихнуть
    ];

    public function student(): BelongsTo{
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam(): BelongsTo{
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function answers():HasMany{
        return $this->hasMany(StudentAnswer::class, 'exam_attempt_id');
    }

    public function result(): BelongsTo{
        return $this->belongsTo(ExamResult::class, 'attempt_id');
    }
}
