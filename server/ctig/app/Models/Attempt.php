<?php

namespace App\Models;

use App\Enums\AttemptStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attempt extends Model
{
    /** @use HasFactory<\Database\Factories\AttemptFactory> */
    use HasFactory;
    protected $fillable = [
        'student_id',
        'exam_id',
        'finished_at',
        'expired_at',
        'status',
        'is_passed',
        'total_mark',
        'started_at'
    ];

    protected $casts = [
        'status' => AttemptStatusEnum::class,
        'expired_at' => 'datetime',
        'finished_at' => 'datetime',
        'started_at' => 'datetime',
        'is_passed' => 'boolean',
    ];

    public function isExpired(){
        return $this->expired_at->isPast();
    }

    public function finish(){
        return $this->status = AttemptStatusEnum::Finished;
    }

    public function student(): BelongsTo{
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam(): BelongsTo{
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function answers():HasMany{
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }

    public function violations(){
        return $this->hasMany(Violation::class, 'attempt_id');
    }
}
