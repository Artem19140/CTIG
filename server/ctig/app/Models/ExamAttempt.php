<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamAttempt extends Model
{
    /** @use HasFactory<\Database\Factories\ExamAttemptFactory> */
    use HasFactory;
    protected $fillable = [
        'student_id',
        'exam_id',
        'finished_at',
        'expired_at',
        'is_banned',
        'status',
        'is_passed'
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
